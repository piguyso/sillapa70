<?php

namespace App\Http\Controllers;

use App\Schoolgroup;
use App\Services\SchoolImportService;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SchoolImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('systemadmin');
    }

    public function template()
    {
        adminchk();

        $groups = Schoolgroup::orderBy('id')->get(['id', 'schoolgroup_name', 'schoolgroup_detail', 'group_type']);
        $spreadsheet = new Spreadsheet();

        // ชีต 1: กรอกข้อมูลโรงเรียน (คอลัมน์ C เลือกรหัสเครือข่ายจากรายการได้)
        $sheet = $spreadsheet->getActiveSheet()->setTitle('Schools');
        $sheet->fromArray([array_merge(SchoolImportService::HEADERS, SchoolImportService::OPTIONAL_HEADERS)], null, 'A1');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:C1')->getFill()->setFillType('solid')->getStartColor()->setRGB('FEF3C7');
        $sheet->getStyle('D1:F1')->getFill()->setFillType('solid')->getStartColor()->setRGB('E2E8F0');
        foreach (['A' => 35, 'B' => 45, 'C' => 20, 'D' => 24, 'E' => 16, 'F' => 16] as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }
        $sheet->freezePane('A2');
        $last = max(2, $groups->count() + 1);
        for ($r = 2; $r <= 502; $r++) {
            $validation = $sheet->getCell("C{$r}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST)->setAllowBlank(true)->setShowDropDown(false)
                ->setShowErrorMessage(true)->setErrorTitle('รหัสเครือข่ายไม่ถูกต้อง')
                ->setError('เลือกรหัสจากชีต "Networks"')
                ->setFormula1("=Networks!\$A\$2:\$A\${$last}");
        }

        // ชีต 2: รายการเครือข่ายและรหัส (นำรหัสไปกรอกในคอลัมน์ school_group_id)
        $networkSheet = $spreadsheet->createSheet()->setTitle('Networks');
        $networkSheet->fromArray([['school_group_id', 'schoolgroup_name', 'ประเภท', 'รายละเอียด']], null, 'A1');
        $networkSheet->fromArray(
            $groups->map(fn ($g) => [$g->id, $g->schoolgroup_name, Schoolgroup::TYPES[$g->group_type] ?? 'กลุ่มโรงเรียน', $g->schoolgroup_detail])->values()->all(),
            null,
            'A2',
        );
        $networkSheet->getStyle('A1:D1')->getFont()->setBold(true);
        $networkSheet->getStyle('A1:D1')->getFill()->setFillType('solid')->getStartColor()->setRGB('E2E8F0');
        foreach (['A' => 20, 'B' => 32, 'C' => 18, 'D' => 45] as $col => $w) {
            $networkSheet->getColumnDimension($col)->setWidth($w);
        }
        $networkSheet->freezePane('A2');

        // ชีต 3: คำอธิบายการกรอก
        $guide = $spreadsheet->createSheet()->setTitle('วิธีกรอก');
        $guide->fromArray([
            ['คอลัมน์', 'ต้องกรอก', 'คำอธิบาย'],
            ['school_name', 'ใช่', 'ชื่อโรงเรียน (ห้ามซ้ำกับที่มีอยู่ในระบบ)'],
            ['school_detail', 'ใช่', 'รายละเอียดโรงเรียน เช่น อำเภอ/ตำบล'],
            ['school_group_id', 'ใช่', 'รหัสเครือข่ายหลัก เลือกจากรายการ หรือดูรหัสในชีต Networks'],
            ['extra_group_ids', 'ไม่บังคับ', 'รหัสเครือข่ายเพิ่มเติมของโรงเรียนที่สังกัดหลายเครือข่าย คั่นด้วยจุลภาค เช่น 3,5'],
            ['is_expansion', 'ไม่บังคับ', 'โรงเรียนขยายโอกาส (มีระดับมัธยม) พิมพ์ 1 ถ้าใช่ เว้นว่างถ้าไม่ใช่'],
            ['school_code', 'ไม่บังคับ', 'รหัส SMIS 8 หลัก ถ้าเว้นว่าง ระบบกำหนดรหัส 86019xxx ให้ และสร้างบัญชีผู้ใช้โรงเรียน (username = รหัส, รหัสผ่านเริ่มต้นเท่ากัน)'],
            ['', '', 'ใส่ได้ครั้งละไม่เกิน 500 โรงเรียน และอย่าแก้ชื่อหัวตารางในชีต Schools'],
        ], null, 'A1');
        $guide->getStyle('A1:C1')->getFont()->setBold(true);
        foreach (['A' => 20, 'B' => 12, 'C' => 80] as $col => $w) {
            $guide->getColumnDimension($col)->setWidth($w);
        }
        $spreadsheet->setActiveSheetIndex(0);

        return response()->streamDownload(
            fn () => (new Xlsx($spreadsheet))->save('php://output'),
            'school-import-template.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        );
    }

    public function inspect(Request $request, SchoolImportService $service)
    {
        adminchk();
        $request->validate([
            'school_file' => ['required', 'file', 'mimes:xlsx,xls', 'max:2048'],
        ]);

        $upload = $request->file('school_file');
        if (! $upload->isValid() || ! is_file($upload->getPathname())) {
            \Illuminate\Support\Facades\Log::error('school import: uploaded temp file missing', ['path' => $upload->getPathname(), 'error' => $upload->getErrorMessage()]);

            return back()->withErrors(['school_file' => 'เซิร์ฟเวอร์เก็บไฟล์ที่อัปโหลดไม่ได้ (โฟลเดอร์ temp ของ PHP ไม่มีสิทธิ์เขียน) กรุณาแจ้งผู้ดูแลเซิร์ฟเวอร์']);
        }

        try {
            $result = $service->inspect($upload);
        } catch (\Throwable $exception) {
            \Illuminate\Support\Facades\Log::error('school import inspect failed', ['e' => $exception]);

            return back()->withErrors(['school_file' => 'ไม่สามารถอ่านไฟล์ Excel ได้ กรุณาใช้เทมเพลตจากระบบ ('.class_basename($exception).': '.$exception->getMessage().')']);
        }

        $hasRowErrors = collect($result['rows'])->contains(fn ($row) => $row['errors'] !== []);
        if ($result['errors'] !== [] || $hasRowErrors) {
            $request->session()->forget('school_import_rows');
        } else {
            $request->session()->put('school_import_rows', $result['rows']);
        }

        return back()->with('school_import_result', $result);
    }

    public function store(Request $request, SchoolImportService $service)
    {
        adminchk();
        $rows = $request->session()->pull('school_import_rows');
        if (! is_array($rows) || $rows === []) {
            return back()->withErrors(['school_file' => 'ข้อมูลตรวจสอบหมดอายุ กรุณาอัปโหลดและตรวจสอบไฟล์อีกครั้ง']);
        }

        try {
            $count = $service->import($rows);
        } catch (\Throwable $exception) {
            return back()->withErrors(['school_file' => $exception->getMessage()]);
        }

        return redirect()->route('school.index')->with('massage', "นำเข้าโรงเรียนสำเร็จ {$count} แห่ง");
    }

    /** ลบโรงเรียนทั้งหมดเพื่อนำเข้าใหม่ (ลบไม่ได้ถ้ามีข้อมูลลงทะเบียน/นักเรียนอ้างอิง) */
    public function purge(Request $request)
    {
        if (trim((string) $request->confirm_text) !== 'ลบโรงเรียนทั้งหมด') {
            return back()->withErrors(['confirm_text' => 'ข้อความยืนยันไม่ถูกต้อง']);
        }

        $db = \Illuminate\Support\Facades\DB::class;
        // หาทุกตารางที่มีคอลัมน์อ้างอิงโรงเรียน (school / school_id) ยกเว้นตารางที่ลบพร้อมกัน
        $columns = $db::select("select table_name as t, column_name as c from information_schema.columns where table_schema = database() and column_name in ('school','school_id') and table_name not in ('schools','school_schoolgroup')");
        $blocking = [];
        foreach ($columns as $col) {
            $q = $db::table($col->t)->whereNotNull($col->c)->where($col->c, '>', 0);
            if ($col->t === 'users') {
                $q->where('position', '!=', 0); // บัญชีโรงเรียนจะถูกลบพร้อมกัน
            }
            $n = (clone $q)->count();
            if ($n > 0) {
                $blocking[] = "ตาราง {$col->t} (คอลัมน์ {$col->c}): {$n} รายการ";
            }
        }
        if ($blocking) {
            $message = "ลบโรงเรียนทั้งหมดไม่ได้ เพราะยังมีข้อมูลอ้างอิงโรงเรียนอยู่:\n- ".implode("\n- ", $blocking)
                ."\nต้องลบหรือย้ายข้อมูลลงทะเบียน/นักเรียนเหล่านี้ก่อน (ลบข้อมูลของงานที่ไม่ใช้แล้วก่อนได้)";
            return back()->withErrors(['confirm_text' => nl2br(e($message), false)])->with('error', $message);
        }
        $dir = storage_path('app/backups');
        @mkdir($dir, 0775, true);
        $db = \Illuminate\Support\Facades\DB::class;
        file_put_contents($dir.'/schools_'.date('Ymd_His').'.json', json_encode([
            'schools' => \Illuminate\Support\Facades\DB::table('schools')->get(),
            'school_schoolgroup' => \Illuminate\Support\Facades\DB::table('school_schoolgroup')->get(),
            'users' => \Illuminate\Support\Facades\DB::table('users')->where('position', 0)->get(),
        ], JSON_UNESCAPED_UNICODE));

        try {
            $count = \Illuminate\Support\Facades\DB::transaction(function () {
            $db = \Illuminate\Support\Facades\DB::table('users')->where('position', 0)->delete();
            \Illuminate\Support\Facades\DB::table('school_schoolgroup')->delete();
            return \Illuminate\Support\Facades\DB::table('schools')->delete();
            });
        } catch (\Throwable $e) {
            \Log::error('school purge failed: '.$e->getMessage());
            return back()->with('error', 'ลบไม่สำเร็จ (ไม่มีการเปลี่ยนแปลงข้อมูล): '.$e->getMessage())->withErrors(['confirm_text' => 'ลบไม่สำเร็จ: '.e($e->getMessage())]);
        }

        return redirect('school')->with('massage', "ลบโรงเรียน {$count} แห่งและบัญชีโรงเรียนแล้ว พร้อมนำเข้าใหม่ (สำรองไว้ที่ storage/app/backups)");
    }
}
