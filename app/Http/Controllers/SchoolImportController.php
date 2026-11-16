<?php

namespace App\Http\Controllers;

use App\Schoolgroup;
use App\Services\SchoolImportService;
use Illuminate\Http\Request;
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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setTitle('Schools');
        $sheet->fromArray([SchoolImportService::HEADERS], null, 'A1');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(45);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->freezePane('A2');

        $networkSheet = $spreadsheet->createSheet()->setTitle('Networks');
        $networkSheet->fromArray([['school_group_id', 'schoolgroup_name']], null, 'A1');
        $networkSheet->fromArray(
            Schoolgroup::orderBy('id')->get(['id', 'schoolgroup_name'])->map->only(['id', 'schoolgroup_name'])->values()->all(),
            null,
            'A2',
        );
        $networkSheet->getStyle('A1:B1')->getFont()->setBold(true);
        $networkSheet->getColumnDimension('A')->setWidth(20);
        $networkSheet->getColumnDimension('B')->setWidth(45);
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
            return back()->withErrors(['school_file' => 'ไม่สามารถอ่านไฟล์ Excel ได้ กรุณาใช้เทมเพลตจากระบบ']);
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
