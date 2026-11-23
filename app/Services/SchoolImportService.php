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

    public function inspect(UploadedFile $file): array
    {
        $sheet = IOFactory::load($file->getRealPath())->getActiveSheet();
        $lastRow = min($sheet->getHighestDataRow(), 502);
        $headers = array_map(fn ($value) => trim((string) $value), $sheet->rangeToArray('A1:C1')[0]);
        $errors = [];

        if ($headers !== self::HEADERS) {
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
            [$name, $detail, $groupId] = array_map(
                fn ($value) => trim((string) $value),
                $sheet->rangeToArray("A{$rowNumber}:C{$rowNumber}")[0],
            );

            if ($name === '' && $detail === '' && $groupId === '') {
                continue;
            }

            $rowErrors = [];
            $normalizedName = $this->normalizeName($name);
            if ($name === '') {
                $rowErrors[] = 'ไม่ระบุชื่อโรงเรียน';
            } elseif ($existingNames->has($normalizedName)) {
                $rowErrors[] = 'ชื่อโรงเรียนซ้ำกับฐานข้อมูล';
            } elseif (isset($seenNames[$normalizedName])) {
                $rowErrors[] = 'ชื่อโรงเรียนซ้ำในไฟล์ (ซ้ำกับแถว '.$seenNames[$normalizedName].')';
            }

            if ($detail === '') {
                $rowErrors[] = 'ไม่ระบุรายละเอียดโรงเรียน';
            }

            if (! ctype_digit($groupId) || ! $groups->has((int) $groupId)) {
                $rowErrors[] = 'ไม่พบรหัสเครือข่าย '.$groupId;
            }

            if ($normalizedName !== '') {
                $seenNames[$normalizedName] ??= $rowNumber;
            }

            $rows[] = [
                'row' => $rowNumber,
                'school_name' => $name,
                'school_detail' => $detail,
                'school_group' => ctype_digit($groupId) ? (int) $groupId : null,
                'schoolgroup_name' => ctype_digit($groupId) ? $groups->get((int) $groupId) : null,
                'errors' => $rowErrors,
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
                if (isset($existingLookup[$this->normalizeName($row['school_name'])])) {
                    throw new \RuntimeException('พบชื่อโรงเรียนซ้ำก่อนนำเข้า: '.$row['school_name']);
                }
                if (! $validGroups->has((int) $row['school_group'])) {
                    throw new \RuntimeException('ไม่พบเครือข่ายของโรงเรียน: '.$row['school_name']);
                }
            }

            foreach ($rows as $row) {
                $school = new School();
                $school->school_name = $row['school_name'];
                $school->school_detail = $row['school_detail'];
                $school->school_group = $row['school_group'];
                $school->save();
                $school->syncNetworks([], (int) $row['school_group']);
            }

            return count($rows);
        });
    }

    private function normalizeName(?string $name): string
    {
        return mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) $name)));
    }
}
