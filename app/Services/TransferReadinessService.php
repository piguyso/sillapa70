<?php

namespace App\Services;

use App\Activities;
use App\Configs;
use App\Addschool;
use App\addschools_areas;
use App\Schoolgroup;
use Illuminate\Database\Eloquent\Builder;

/**
 * ตรวจความพร้อมก่อนโอนข้อมูลจากระดับเครือข่ายไประดับเขตพื้นที่
 * รายการ (เครือข่าย + กิจกรรม) ถือว่า "ประกาศผลแล้ว" เมื่อทุกทีมมี activity_status เป็น 1, 98 หรือ 99
 */
class TransferReadinessService
{
    public const ANNOUNCED_STATUSES = [1, 98, 99];
    public const NO_CONTEST_STATUSES = [98, 99];

    /** จำนวนตัวแทนเครือข่ายที่เข้าระดับเขตเมื่อยังไม่ได้ตั้งค่า (อันดับ 1–3) */
    public const DEFAULT_REPRESENTATIVES = 3;

    public const MAX_REPRESENTATIVES = 10;

    /** จำนวนตัวแทนที่ตั้งไว้ในหน้าตั้งค่าของครั้งการแข่งขันที่เปิดอยู่ (อันดับ 1..N) */
    public static function representativeCount(?int $eventId = null): int
    {
        $eventId ??= active_event_id();
        $count = $eventId
            ? (int) Configs::where('event_id', $eventId)->where('schoolgroup', 0)->value('representative_count')
            : 0;

        return $count >= 1 ? min($count, self::MAX_REPRESENTATIVES) : self::DEFAULT_REPRESENTATIVES;
    }

    /** @return int[] เช่น [1, 2] */
    public static function transferRanks(?int $eventId = null): array
    {
        return range(1, self::representativeCount($eventId));
    }

    /** ข้อความอันดับที่โอน เช่น "1–2" หรือ "1" */
    public static function transferRanksLabel(?int $eventId = null): string
    {
        $count = self::representativeCount($eventId);

        return $count > 1 ? '1–'.$count : '1';
    }

    /** เงื่อนไขทีมที่ต้องโอน: อันดับตัวแทนตามที่ตั้งในหน้าตั้งค่า (ค่าเริ่มต้น 1–3) ที่ประกาศผลแล้ว หรือรายการที่ไม่มีการแข่งขัน (98/99) */
    public static function applyTransferableScope(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->where(function (Builder $ranked) {
                $ranked->where('activity_status', 1)
                    ->whereIn('activity_rank', self::transferRanks())
                    ->where('student_score', '>', 0);
            })->orWhereIn('activity_status', self::NO_CONTEST_STATUSES);
        });
    }

    public function summary(): array
    {
        $statuses = implode(',', self::ANNOUNCED_STATUSES);
        $groups = addschools_areas::query()
            ->select('school_schoolgroup', 'school_activity')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN activity_status IN ($statuses) THEN 1 ELSE 0 END) as announced")
            ->groupBy('school_schoolgroup', 'school_activity')
            ->get();

        $networkNames = Schoolgroup::whereIn('id', $groups->pluck('school_schoolgroup')->unique())
            ->pluck('schoolgroup_name', 'id');
        $activityNames = Activities::whereIn('id', $groups->pluck('school_activity')->unique())
            ->pluck('activity_name', 'id');

        $networks = $groups->groupBy('school_schoolgroup')->map(function ($rows, $networkId) use ($networkNames, $activityNames) {
            $pending = $rows->filter(fn ($row) => (int) $row->announced < (int) $row->total);

            return [
                'id' => (int) $networkId,
                'name' => $networkNames->get($networkId, 'เครือข่าย ID '.$networkId),
                'activities' => $rows->count(),
                'announced' => $rows->count() - $pending->count(),
                'ready' => $pending->isEmpty(),
                'pending' => $pending->map(fn ($row) => $activityNames->get($row->school_activity, 'กิจกรรม ID '.$row->school_activity))
                    ->sort()->values()->all(),
            ];
        })->sortBy('name')->values();

        $transferable = self::applyTransferableScope(addschools_areas::query());
        $districtTeams = Addschool::query();

        return [
            'ready' => $networks->isNotEmpty() && $networks->every(fn ($n) => $n['ready']),
            'networks' => $networks->all(),
            'pending_networks' => $networks->where('ready', false)->count(),
            'pending_items' => $networks->sum(fn ($n) => count($n['pending'])),
            'transferable_teams' => (clone $transferable)->count(),
            'transfer_ranks_label' => self::transferRanksLabel(),
            'no_contest_teams' => (clone $transferable)->whereIn('activity_status', self::NO_CONTEST_STATUSES)->count(),
            'district_teams' => (clone $districtTeams)->count(),
            'district_scored_teams' => (clone $districtTeams)->where(function ($q) {
                $q->where('student_score', '>', 0)->orWhere('activity_status', '!=', 0);
            })->count(),
        ];
    }
}
