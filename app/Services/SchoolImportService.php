<?php

namespace App\Services;

use App\School;
use App\Schoolgroup;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SchoolImportService
{
    private static ?int $privateCounter = null;

    public const HEADERS = ['school_name', 'school_detail', 'school_group_id'];

    /** คอลัมน์เสริม (ไม่บังคับ): เครือข่ายเพิ่มเติม (คั่นด้วยจุลภาค) และป้ายโรงเรียนขยายโอกาส (1 = ใช่) */
    public const OPTIONAL_HEADERS = ['extra_group_ids', 'is_expansion', 'school_code'];

    public function inspect(UploadedFile $file, bool $update = false): array
    {
        $sheet = IOFactory::load($file->getPathname())->getActiveSheet();
        $lastRow = min($sheet->getHighestDataRow(), 502);
        $headers = array_map(fn ($value) => trim((string) $value), $sheet->rangeToArray('A1:F1')[0]);
        $optional = array_slice($headers, 3, 3);
        $headers = array_slice($headers, 0, 3);
        $errors = [];

        $optionalOk = true;
        foreach ($optional as $i => $h) {
            if ($h !== '' && $h !== self::OPTIONAL_HEADERS[$i]) {
                $optionalOk = false;
            }
        }
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
        $existingCodes = School::whereNotNull('school_code')->pluck('school_code')->flip();
        $seenCodes = [];
        $seenUpdates = [];
        $byCode = School::whereNotNull('school_code')->where('school_code', '!=', '')->pluck('id', 'school_code');
        $byName = [];
        foreach (School::get(['id', 'school_name', 'school_code']) as $sc) {
            $byName[$this->normalizeName($sc->school_name)] ??= ['id' => $sc->id, 'code' => (string) $sc->school_code];
        }
        $rows = [];

        for ($rowNumber = 2; $rowNumber <= $lastRow; $rowNumber++) {
            [$name, $detail, $groupId, $extraRaw, $expansionRaw, $codeRaw] = array_pad(array_map(
                fn ($value) => trim((string) $value),
                $sheet->rangeToArray("A{$rowNumber}:F{$rowNumber}")[0],
            ), 6, '');

            if ($name === '' && $detail === '' && $groupId === '' && $extraRaw === '') {
                continue;
            }

            $rowErrors = [];
            $skipReason = null;
            $normalizedName = $this->normalizeName($name);
            $codeRaw = preg_replace('/\D/', '', str_replace(['๐','๑','๒','๓','๔','๕','๖','๗','๘','๙'], range(0, 9), $codeRaw));
            $matchId = null;
            if ($update) {
                if ($codeRaw !== '' && $byCode->has($codeRaw)) {
                    $matchId = (int) $byCode[$codeRaw];
                } elseif (isset($byName[$normalizedName]) && ($codeRaw === '' || $byName[$normalizedName]['code'] === '')) {
                    $matchId = (int) $byName[$normalizedName]['id'];
                }
                if ($matchId !== null && isset($seenUpdates[$matchId])) {
                    $skipReason = 'โรงเรียนเดียวกันซ้ำในไฟล์ (ซ้ำกับแถว '.$seenUpdates[$matchId].')';
                    $matchId = null;
                } elseif ($matchId !== null) {
                    $seenUpdates[$matchId] = $rowNumber;
                }
            }
            if ($name === '') {
                $rowErrors[] = 'ไม่ระบุชื่อโรงเรียน';
            } elseif ($skipReason !== null) {
                // ซ้ำในไฟล์ (ตรวจแล้วด้านบน)
            } elseif ($existingNames->has($normalizedName) && $matchId === null) {
                $skipReason = 'ชื่อโรงเรียนซ้ำกับฐานข้อมูล';
            } elseif (isset($seenNames[$normalizedName])) {
                $skipReason = 'ชื่อโรงเรียนซ้ำในไฟล์ (ซ้ำกับแถว '.$seenNames[$normalizedName].')';
            }

            if ($detail === '') {
                $rowErrors[] = 'ไม่ระบุรายละเอียดโรงเรียน';
            }

            $codeRaw = preg_replace('/\D/', '', str_replace(['๐','๑','๒','๓','๔','๕','๖','๗','๘','๙'], range(0, 9), $codeRaw));
            if ($codeRaw !== '' && ! preg_match('/^\d{8}$/', $codeRaw)) {
                $rowErrors[] = 'รหัสโรงเรียนต้องเป็นตัวเลข 8 หลัก';
            } elseif ($codeRaw !== '' && $skipReason === null && $matchId === null) {
                if ($existingCodes->has($codeRaw)) {
                    $skipReason = 'รหัสโรงเรียนซ้ำกับฐานข้อมูล';
                } elseif (isset($seenCodes[$codeRaw])) {
                    $skipReason = 'รหัสโรงเรียนซ้ำในไฟล์ (ซ้ำกับแถว '.$seenCodes[$codeRaw].')';
                } else {
                    $seenCodes[$codeRaw] = $rowNumber;
                }
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
                'school_code' => $codeRaw,
                'update_id' => $matchId,
                'errors' => $rowErrors,
                'skip' => $skipReason,
            ];
        }

        if ($rows === []) {
            $errors[] = 'ไม่พบข้อมูลโรงเรียนในไฟล์';
        }

        return ['rows' => $rows, 'errors' => $errors];
    }

    /** @return array{created:int,updated:int} */
    public function import(array $rows): array
    {
        self::$privateCounter = null;
        return DB::transaction(function () use ($rows): array {
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
            $updated = 0;
            foreach ($rows as $row) {
                if (! empty($row['update_id']) && empty($row['skip'])) {
                    $this->updateExisting($row);
                    $updated++;
                    continue;
                }
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
                $code = (string) ($row['school_code'] ?? '');
                if ($code === '' || School::where('school_code', $code)->exists()) {
                    $code = $this->nextPrivateCode();
                }
                $school->school_code = $code;
                $school->save();
                $this->createAccount($school);
                $school->syncNetworks($row['extra_groups'] ?? [], (int) $row['school_group']);
                $imported++;
            }

            return ['created' => $imported, 'updated' => $updated];
        });
    }

    /** อัปเดตโรงเรียนเดิมโดยคง id (ข้อมูลลงทะเบียน/นักเรียนที่อ้างอิงอยู่ไม่กระทบ) */
    private function updateExisting(array $row): void
    {
        $school = School::lockForUpdate()->findOrFail($row['update_id']);
        $school->school_name = $row['school_name'];
        $school->school_detail = $row['school_detail'];
        $school->school_group = $row['school_group'];
        $school->is_expansion = (bool) ($row['is_expansion'] ?? false);
        $code = (string) ($row['school_code'] ?? '');
        if ((string) $school->school_code === '') {
            $school->school_code = ($code !== '' && ! School::where('school_code', $code)->exists()) ? $code : $this->nextPrivateCode();
        }
        $school->save();
        $school->syncNetworks($row['extra_groups'] ?? [], (int) $row['school_group']);
        \App\User::where('position', 0)->where('school_id', $school->id)->update(['schoolgroup' => $school->school_group]);
        if (! \App\User::where('position', 0)->where('school_id', $school->id)->exists()) {
            $this->createAccount($school);
        }
    }

    /** รหัสโรงเรียนเอกชน/ไม่มีใน SMIS: 86019xxx ต่อจากลำดับล่าสุด */
    private function nextPrivateCode(): string
    {
        self::$privateCounter ??= (int) School::where('school_code', 'like', '86019%')->max('school_code');
        do {
            self::$privateCounter = max(self::$privateCounter + 1, 86019001);
            $code = (string) self::$privateCounter;
        } while (School::where('school_code', $code)->exists());

        return $code;
    }

    /** บัญชีโรงเรียน: username = รหัส 8 หลัก, รหัสผ่านเริ่มต้นเท่ากัน, บังคับเปลี่ยน */
    private function createAccount(School $school): void
    {
        if (\App\User::where('email', $school->school_code)->exists()) {
            return;
        }
        $u = new \App\User();
        $u->name = $school->school_name;
        $u->lastname = (string) $school->school_detail;
        $u->email = $school->school_code;
        $u->password = \Illuminate\Support\Facades\Hash::make($school->school_code, ['rounds' => 8]) /* รหัสเริ่มต้นชั่วคราว บังคับเปลี่ยนตอนเข้าใช้ครั้งแรก (ครั้งนั้นใช้ rounds ปกติ) ลดเวลานำเข้าจำนวนมาก */;
        $u->must_change_password = 1;
        $u->position = 0;
        $u->school_id = $school->id;
        $u->schoolgroup = $school->school_group;
        $u->branch = $school->id;
        $u->save();
    }

    private function normalizeName(?string $name): string
    {
        return mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) $name)));
    }
}
