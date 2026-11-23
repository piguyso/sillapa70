<?php

namespace App\Services;

use App\School;
use App\Schoolgroup;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SchoolImportService
{
    public const HEADERS = ['school_name', 'school_detail', 'school_group_id'];

    /** คอลัมน์เสริม (ไม่บังคับ): เครือข่ายเพิ่มเติม (คั่นด้วยจุลภาค) และป้ายโรงเรียนขยายโอกาส (1 = ใช่) */
    public const OPTIONAL_HEADERS = ['extra_group_ids', 'is_expansion'];

    public function inspect(UploadedFile $file): array
    {
        $sheet = IOFactory::load($file->getPathname())->getActiveSheet();
        $lastRow = min($sheet->getHighestDataRow(), 502);
        $headers = array_map(fn ($value) => trim((string) $value), $sheet->rangeToArray('A1:E1')[0]);
        $optional = array_slice($headers, 3, 2);
        $headers = array_slice($headers, 0, 3);
        $errors = [];

        $optionalOk = $optional === ['', ''] || $optional === self::OPTIONAL_HEADERS || $optional === [self::OPTIONAL_HEADERS[0], ''] || $optional === [self::OPTIONAL_HEADERS[0]];
        if ($headers !== self::HEADERS || ! $optionalOk) {
            return ['rows' => [], 'errors' => ['หัวตารางไม่ถูกต้อง กรุณาใช้ไฟล์เทมเพลตจากระบบ']];
        }

        if ($sheet->getHighestDataRow() > 502) {
            $errors[] = 'ไฟล์มีข้อมูลเกิน 500 โรงเรียน';
        }

        $groups = Schoolgroup::pluck('schoolgroup_name', 'id');
        $existingNames = School::pluck('school_name')
            ->mapWithKeys(fn ($name) => [$this->normalizeName($name) => true]);
        $seenNames = [];
        $rows = [];

        for ($rowNumber = 2; $rowNumber <= $lastRow; $rowNumber++) {
            [$name, $detail, $groupId, $extraRaw, $expansionRaw] = array_pad(array_map(
                fn ($value) => trim((string) $value),
                $sheet->rangeToArray("A{$rowNumber}:E{$rowNumber}")[0],
            ), 5, '');

            if ($name === '' && $detail === '' && $groupId === '' && $extraRaw === '') {
                continue;
            }

            $rowErrors = [];
            $skipReason = null;
            $normalizedName = $this->normalizeName($name);
            if ($name === '') {
                $rowErrors[] = 'ไม่ระบุชื่อโรงเรียน';
            } elseif ($existingNames->has($normalizedName)) {
                $skipReason = 'ชื่อโรงเรียนซ้ำกับฐานข้อมูล';
            } elseif (isset($seenNames[$normalizedName])) {
                $skipReason = 'ชื่อโรงเรียนซ้ำในไฟล์ (ซ้ำกับแถว '.$seenNames[$normalizedName].')';
            }

            if ($detail === '') {
                $rowErrors[] = 'ไม่ระบุรายละเอียดโรงเรียน';
            }

            if (! ctype_digit($groupId) || ! $groups->has((int) $groupId)) {
                $rowErrors[] = 'ไม่พบรหัสเครือข่าย '.$groupId;
            }

            $extraIds = [];
            if ($extraRaw !== '') {
                foreach (preg_split('/[\s,;]+/', $extraRaw, -1, PREG_SPLIT_NO_EMPTY) as $part) {
                    if (! ctype_digit($part) || ! $groups->has((int) $part)) {
                        $rowErrors[] = 'ไม่พบรหัสเครือข่ายเพิ่มเติม '.$part;
                    } else {
                        $extraIds[] = (int) $part;
                    }
                }
            }
            $isExpansion = in_array(mb_strtolower($expansionRaw), ['1', 'y', 'yes', 'ใช่', 'true'], true);

            if ($normalizedName !== '') {
                $seenNames[$normalizedName] ??= $rowNumber;
            }

            $rows[] = [
                'row' => $rowNumber,
                'school_name' => $name,
                'school_detail' => $detail,
                'school_group' => ctype_digit($groupId) ? (int) $groupId : null,
                'schoolgroup_name' => ctype_digit($groupId) ? $groups->get((int) $groupId) : null,
                'extra_groups' => array_values(array_unique($extraIds)),
                'extra_names' => collect($extraIds)->map(fn ($id) => $groups->get($id))->filter()->implode(', '),
                'is_expansion' => $isExpansion,
                'errors' => $rowErrors,
                'skip' => $skipReason,
            ];
        }

        if ($rows === []) {
            $errors[] = 'ไม่พบข้อมูลโรงเรียนในไฟล์';
        }

        return ['rows' => $rows, 'errors' => $errors];
    }

    public function import(array $rows): int
    {
        return DB::transaction(function () use ($rows): int {
            $existingNames = School::lockForUpdate()->pluck('school_name')
                ->map(fn ($name) => $this->normalizeName($name))
                ->all();
            $existingLookup = array_fill_keys($existingNames, true);
            $validGroups = Schoolgroup::pluck('id')->mapWithKeys(fn ($id) => [(int) $id => true]);

            foreach ($rows as $row) {
                if (! $validGroups->has((int) $row['school_group'])) {
                    throw new \RuntimeException('ไม่พบเครือข่ายของโรงเรียน: '.$row['school_name']);
                }
            }

            $imported = 0;
            foreach ($rows as $row) {
                $key = $this->normalizeName($row['school_name']);
                if (! empty($row['skip']) || isset($existingLookup[$key])) {
                    continue;
                }
                $existingLookup[$key] = true;
                $school = new School();
                $school->school_name = $row['school_name'];
                $school->school_detail = $row['school_detail'];
                $school->school_group = $row['school_group'];
                $school->is_expansion = (bool) ($row['is_expansion'] ?? false);
                $school->save();
                $school->syncNetworks($row['extra_groups'] ?? [], (int) $row['school_group']);
                $imported++;
            }

            return $imported;
        });
    }

    private function normalizeName(?string $name): string
    {
        return mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) $name)));
    }
}
