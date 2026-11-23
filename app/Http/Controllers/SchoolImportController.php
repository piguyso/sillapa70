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
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:C1')->getFill()->setFillType('solid')->getStartColor()->setRGB('FEF3C7');
        $sheet->getStyle('D1:E1')->getFill()->setFillType('solid')->getStartColor()->setRGB('E2E8F0');
        foreach (['A' => 35, 'B' => 45, 'C' => 20, 'D' => 24, 'E' => 16] as $col => $w) {
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

        try {
            $result = $service->inspect($request->file('school_file'));
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
}
