<?php

namespace App\Http\Controllers\Concerns;

use App\Activities;
use App\Addschool;
use Illuminate\Support\Facades\Auth;

/**
 * สิทธิ์จัดการข้อมูลระดับเขตพื้นที่
 * - ผู้ดูแลระบบ (10): ทุกกิจกรรม
 * - ศูนย์การแข่งขัน (1): เฉพาะกิจกรรมที่อยู่ในสาขา (activity_branch) ของตนเอง
 * ส่วนการเข้าถึงตามตำแหน่งตรวจโดย middleware score.district (อนุญาตเฉพาะ 1 และ 10)
 */
trait AuthorizesDistrictBranch
{
    protected function authorizeDistrictActivity($activityId): Activities
    {
        $activity = Activities::findOrFail((int) $activityId);
        if ((int) Auth::user()->position < 10
            && (int) $activity->activity_branch !== (int) Auth::user()->branch) {
            abort(403, 'ไม่มีสิทธิ์จัดการรายการแข่งขันของศูนย์การแข่งขันอื่น');
        }

        return $activity;
    }

    protected function authorizeDistrictTeam($teamId): Addschool
    {
        $team = Addschool::findOrFail((int) $teamId);
        $this->authorizeDistrictActivity($team->school_activity);

        return $team;
    }
}
