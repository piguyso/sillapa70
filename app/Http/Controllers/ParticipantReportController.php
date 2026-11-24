<?php

namespace App\Http\Controllers;

use App\Activities;
use App\Classes;
use App\Schoolgroup;
use App\Services\ParticipantReportService as Report;
use App\Subjectgroup;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * รายงานตาราง (participants / schools / activities) + ส่งออก Excel
 * เปิดสาธารณะ (ไม่ต้อง login) เหมือนหน้า showall_print
 * - ระดับเขตพื้นที่: ทุกเครือข่าย (กรองเครือข่ายได้)
 * - ระดับเครือข่าย: เฉพาะเครือข่ายที่ระบุใน URL
 */
class ParticipantReportController extends Controller
{
    public function district(Request $request, Report $report, $type)
    {
        return $this->page($request, $report, $this->type($type), Report::LEVEL_DISTRICT);
    }

    public function districtExport(Request $request, Report $report, $type)
    {
        return $this->export($request, $report, $this->type($type), Report::LEVEL_DISTRICT);
    }

    public function network(Request $request, Report $report, $type, $id)
    {
        return $this->page($request, $report, $this->type($type), Report::LEVEL_NETWORK, (int) $id);
    }

    public function networkExport(Request $request, Report $report, $type, $id)
    {
        return $this->export($request, $report, $this->type($type), Report::LEVEL_NETWORK, (int) $id);
    }

    /** ลิงก์เก่า /showall_print3 และ /showall_print6 (ทั้งเขตและเครือข่าย) พาไปหน้ารายงานใหม่ */
    public function legacyDistrict($type)
    {
        return redirect(url('report/'.$this->type($type)));
    }

    public function legacyNetwork($id, $type)
    {
        return redirect(url('area/report/'.$this->type($type).'/'.(int) $id));
    }

    private function type($type): string
    {
        abort_unless(isset(Report::TYPES[$type]), 404);

        return $type;
    }

    private function filters(Request $request, string $level, ?int $networkId = null): array
    {
        $filters = [
            'subject' => $request->integer('subject') ?: null,
            'class' => $request->integer('class') ?: null,
            'activity' => $request->integer('activity') ?: null,
            'schoolgroup' => $request->integer('schoolgroup') ?: null,
            'top3' => $request->boolean('top3'),
            'teams' => in_array($request->input('teams'), array_keys(\App\Services\ParticipantReportService::TEAM_FILTERS), true) ? $request->input('teams') : '',
        ];
        if ($level === Report::LEVEL_NETWORK) {
            // หน้าเครือข่ายแสดงเฉพาะเครือข่ายใน URL เสมอ (ไม่รับค่าจาก query)
            Schoolgroup::findOrFail($networkId);
            $filters['schoolgroup'] = $networkId;
        }

        return $filters;
    }

    private function page(Request $request, Report $report, string $type, string $level, ?int $networkId = null)
    {
        $filters = $this->filters($request, $level, $networkId);
        $activities = Activities::query()
            ->when($filters['subject'], fn ($q, $s) => $q->where('activity_subject', $s))
            ->when($filters['class'], fn ($q, $c) => $q->where('activity_class', $c))
            ->orderBy('activity_subject')->orderBy('id')->get(['id', 'activity_name', 'activity_subject']);
        $base = $level === Report::LEVEL_NETWORK ? 'area/report/%s/'.$networkId : 'report/%s';

        return view('report.table', [
            'type' => $type,
            'types' => Report::TYPES,
            'level' => $level,
            'networkId' => $networkId,
            'filters' => $filters,
            'filterSummary' => $report->filterSummary($filters, $type, $level),
            'columns' => $report->columns($type, $level),
            'rows' => $report->rows($type, $level, $filters),
            'subjects' => Subjectgroup::orderBy('id')->get(),
            'classes' => Classes::orderBy('id')->get(),
            'activities' => $activities,
            'schoolgroups' => $level === Report::LEVEL_DISTRICT ? Schoolgroup::orderBy('id')->get() : collect(),
            'networkName' => $level === Report::LEVEL_NETWORK ? (Schoolgroup::find($networkId)->schoolgroup_name ?? '') : '',
            'tabUrls' => collect(Report::TYPES)->map(fn ($t, $k) => url(sprintf($base, $k)))->all(),
            'exportUrl' => url(sprintf($base, $type).'/export')
                .'?'.http_build_query($request->only(['subject', 'class', 'activity', 'schoolgroup', 'top3', 'teams'])),
        ]);
    }

    private function export(Request $request, Report $report, string $type, string $level, ?int $networkId = null)
    {
        $filters = $this->filters($request, $level, $networkId);
        $columns = $report->columns($type, $level);
        $rows = $report->rows($type, $level, $filters);
        $levelName = $level === Report::LEVEL_NETWORK
            ? (Schoolgroup::find($networkId)->schoolgroup_name ?? '')
            : 'ระดับเขตพื้นที่การศึกษา';
        $headers = array_merge(['ลำดับ'], array_column($columns, 'label'));
        $lastCol = Coordinate::stringFromColumnIndex(count($headers));

        $book = new Spreadsheet;
        $sheet = $book->getActiveSheet()->setTitle(mb_substr(Report::TYPES[$type]['file'], 0, 30));
        $book->getDefaultStyle()->getFont()->setName('TH Sarabun New')->setSize(14);

        $sheet->setCellValue('A1', Report::TYPES[$type]['title'].' '.$levelName.' ปีการศึกษา '.active_year()
            .($type === 'participants' && $filters['top3'] ? ' (เฉพาะอันดับ 1-3)' : ''));
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
        $summary = $report->filterSummary($filters, $type, $level);
        $sheet->setCellValue('A2', 'ตัวกรอง: '.($summary ? implode(' | ', $summary) : 'ทั้งหมด'));
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->fromArray($headers, null, 'A3');
        $sheet->getStyle("A3:{$lastCol}3")->getFont()->setBold(true);
        $sheet->getStyle("A3:{$lastCol}3")->getFill()->setFillType('solid')->getStartColor()->setRGB('E2E8F0');

        $r = 4;
        foreach ($rows as $i => $row) {
            $cells = [$i + 1];
            foreach ($columns as $col) {
                $v = $row[$col['key']] ?? null;
                $v = $col['kind'] === 'list' ? implode("\n", (array) $v) : $v;
                $cells[] = is_string($v) ? network_label_text(strtr($v, ['๐' => '0', '๑' => '1', '๒' => '2', '๓' => '3', '๔' => '4', '๕' => '5', '๖' => '6', '๗' => '7', '๘' => '8', '๙' => '9'])) : $v;
            }
            $sheet->fromArray($cells, null, "A{$r}", true);
            $r++;
        }
        $last = max(3, $r - 1);
        $range = "A3:{$lastCol}{$last}";
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
        $sheet->getColumnDimension('A')->setWidth(7);
        foreach ($columns as $k => $col) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($k + 2))->setWidth($col['width']);
        }
        $sheet->freezePane('A4');
        $sheet->setAutoFilter($range);
        $sheet->getPageSetup()->setOrientation('landscape')->setPaperSize(9)->setFitToWidth(1)->setFitToHeight(0);

        $suffix = $summary ? '_'.trim(preg_replace('#[\\/:*?"<>|\s]+#u', '-', implode('_', array_map(fn ($p) => explode(': ', $p, 2)[1] ?? $p, $summary))), '-_') : '';
        $filename = Report::TYPES[$type]['file'].'_'.($level === Report::LEVEL_NETWORK ? 'เครือข่าย' : 'เขตพื้นที่')
            .'_'.active_year().mb_substr($suffix, 0, 80).'_'.now()->format('Ymd').'.xlsx';

        return response()->streamDownload(function () use ($book) {
            (new Xlsx($book))->save('php://output');
        }, $filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }
}
