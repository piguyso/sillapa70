<?php

namespace App\Http\Controllers;

use App\Schoolgroup;
use App\Services\ParticipantReportService as Report;
use App\Services\ResultDashboardService;

/** หน้าสรุป Dashboard ผลการแข่งขัน เปิดสาธารณะ เหมือนหน้ารายงานตาราง */
class ResultDashboardController extends Controller
{
    public function district(ResultDashboardService $service)
    {
        return view('report.dashboard', [
            'level' => Report::LEVEL_DISTRICT,
            'networkId' => null,
            'networkName' => '',
            'd' => $service->build(Report::LEVEL_DISTRICT),
        ]);
    }

    public function network(ResultDashboardService $service, $id)
    {
        $group = Schoolgroup::findOrFail((int) $id);

        return view('report.dashboard', [
            'level' => Report::LEVEL_NETWORK,
            'networkId' => (int) $id,
            'networkName' => $group->schoolgroup_name,
            'd' => $service->build(Report::LEVEL_NETWORK, (int) $id),
        ]);
    }
}
