<?php

namespace App\Services;

use App\Activities;
use App\addschool;
use App\addschools_areas;
use App\Addstudent;
use App\addstudents_areas;
use App\School;
use App\Schoolgroup;
use App\Subjectgroup;
use Illuminate\Support\Collection;

/**
 * ข้อมูลหน้าสรุป Dashboard ผลการแข่งขัน (ระดับเขตพื้นที่ / ระดับเครือข่าย)
 * เหรียญนับเฉพาะทีมที่ประกาศผลแล้ว (activity_status = 1): ทอง >= 80, เงิน 70-79.99, ทองแดง 60-69.99
 */
class ResultDashboardService
{
    public function build(string $level, ?int $networkId = null): array
    {
        $isNetwork = $level === ParticipantReportService::LEVEL_NETWORK;
        $teamModel = $isNetwork ? addschools_areas::class : addschool::class;
        $peopleModel = $isNetwork ? addstudents_areas::class : Addstudent::class;

        $teams = $teamModel::query()
            ->when($networkId, fn ($q) => $q->where('school_schoolgroup', $networkId))
            ->get(['id', 'school', 'school_schoolgroup', 'school_activity', 'student_score', 'activity_rank', 'activity_status']);

        $activities = Activities::query()->get(['id', 'activity_subject', 'activity_class', 'activity_name'])->keyBy('id');
        $subjects = Subjectgroup::orderBy('id')->get()->keyBy('id');

        $medalOf = function ($t): ?string {
            if ((int) $t->activity_status !== 1) {
                return null;
            }
            $s = (float) $t->student_score;

            return $s >= 80 ? 'gold' : ($s >= 70 ? 'silver' : ($s >= 60 ? 'bronze' : 'pass'));
        };

        $blank = fn () => ['teams' => 0, 'gold' => 0, 'silver' => 0, 'bronze' => 0, 'pass' => 0, 'other' => 0, 'pending' => 0];
        $add = function (array &$row, $t) use ($medalOf) {
            $row['teams']++;
            $m = $medalOf($t);
            if ($m === null) {
                $row[(int) $t->activity_status === 0 ? 'pending' : 'other']++;
            } else {
                $row[$m]++;
            }
        };

        $total = $blank();
        $bySubject = [];
        $bySchool = [];
        $byNetwork = [];
        $perActivity = [];
        foreach ($teams as $t) {
            $add($total, $t);
            $subjectId = $activities[$t->school_activity]->activity_subject ?? 0;
            $bySubject[$subjectId] ??= $blank();
            $add($bySubject[$subjectId], $t);
            $bySchool[$t->school] ??= $blank();
            $add($bySchool[$t->school], $t);
            $byNetwork[$t->school_schoolgroup] ??= $blank() + ['schools' => []];
            $add($byNetwork[$t->school_schoolgroup], $t);
            $byNetwork[$t->school_schoolgroup]['schools'][$t->school] = true;
            $perActivity[$t->school_activity]['teams'] = ($perActivity[$t->school_activity]['teams'] ?? 0) + 1;
            $perActivity[$t->school_activity]['announced'] = ($perActivity[$t->school_activity]['announced'] ?? 0) + ((int) $t->activity_status !== 0 ? 1 : 0);
        }

        $schoolNames = School::query()->whereIn('id', array_keys($bySchool))->pluck('school_name', 'id');
        $topSchools = collect($bySchool)
            ->map(fn ($r, $id) => $r + ['id' => $id, 'name' => $schoolNames[$id] ?? '-', 'medals' => $r['gold'] + $r['silver'] + $r['bronze']])
            ->filter(fn ($r) => $r['medals'] > 0)
            ->sortBy([['gold', 'desc'], ['silver', 'desc'], ['bronze', 'desc']])
            ->take(10)->values();

        // ความคืบหน้าการประกาศผล: รายการที่มีทีมลงทะเบียน แยกเป็นประกาศครบ / ประกาศบางส่วน / ยังไม่ประกาศ
        $progress = ['all' => 0, 'part' => 0, 'none' => 0];
        foreach ($perActivity as $p) {
            $progress[$p['announced'] === $p['teams'] ? 'all' : ($p['announced'] > 0 ? 'part' : 'none')]++;
        }

        $bySubjectRows = $subjects->map(fn ($s) => ['name' => $s->subjectgroup_name] + ($bySubject[$s->id] ?? $blank()))->values();

        $networks = collect();
        if (! $isNetwork) {
            $groups = Schoolgroup::orderBy('id')->get();
            $schoolsInGroup = School::query()->get(['id', 'school_group'])->groupBy('school_group')->map->count();
            $networks = $groups->map(function ($g) use ($byNetwork, $blank, $schoolsInGroup) {
                $r = $byNetwork[$g->id] ?? $blank() + ['schools' => []];

                return ['id' => $g->id, 'name' => $g->schoolgroup_name] + $r
                    + ['school_count' => count($r['schools']), 'school_total' => (int) ($schoolsInGroup[$g->id] ?? 0)];
            });
        }

        $teamIds = $teams->pluck('id');
        $people = $peopleModel::query()->whereIn('school', $teamIds)->get(['type']);
        $schoolTotal = School::query()->when($networkId, fn ($q) => $q->where('school_group', $networkId))->count();

        return [
            'total' => $total,
            'medals' => $total['gold'] + $total['silver'] + $total['bronze'],
            'activityTotal' => $activities->count(),
            'activityWithTeams' => count($perActivity),
            'progress' => $progress,
            'schoolsJoined' => count($bySchool),
            'schoolTotal' => $schoolTotal,
            'students' => $people->where('type', 0)->count(),
            'teachers' => $people->where('type', 1)->count(),
            'bySubject' => $bySubjectRows,
            'topSchools' => $topSchools,
            'networks' => $networks,
        ];
    }
}
