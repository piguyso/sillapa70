<?php

namespace App\Services;

use App\addschool;
use App\addschools_areas;
use Illuminate\Support\Collection;

/**
 * รายงานตาราง 3 แบบ ใช้ร่วมกันทั้งหน้าเว็บและไฟล์ Excel (ข้อมูลตรงกันเสมอ)
 *  - participants : 1 แถว = 1 ทีม พร้อมรายชื่อนักเรียนและครูในแถวเดียวกัน
 *  - schools      : 1 แถว = 1 ทีม (รายชื่อโรงเรียนที่เข้าร่วม)
 *  - activities   : 1 แถว = 1 รายการแข่งขันที่มีทีมลงทะเบียน พร้อมจำนวนทีม
 */
class ParticipantReportService
{
    public const LEVEL_DISTRICT = 'district';
    public const LEVEL_NETWORK = 'network';

    public const TYPES = [
        'participants' => ['title' => 'รายงานผู้เข้าร่วมการแข่งขัน', 'file' => 'รายชื่อผู้เข้าร่วม'],
        'schools' => ['title' => 'รายชื่อโรงเรียนที่เข้าร่วมการแข่งขัน', 'file' => 'รายชื่อโรงเรียน'],
        'activities' => ['title' => 'รายการแข่งขันที่มีการลงทะเบียน', 'file' => 'รายการแข่งขัน'],
    ];

    /** @return array<int, array{key:string,label:string,width:int,kind:string}> kind: text|list|num */
    /** ตัวกรองจำนวนทีมที่ลงทะเบียนต่อรายการ (ใช้กับรายงานรายการแข่งขัน) */
    public const TEAM_FILTERS = [
        '' => 'เฉพาะที่มีผู้ลงทะเบียน (ค่าเริ่มต้น)',
        'none' => 'ไม่มีผู้ลงทะเบียน',
        'lt3' => 'ลงทะเบียนน้อยกว่า 3 ราย (1-2 ราย)',
        'eq3' => 'ลงทะเบียน 3 ราย',
        'gt3' => 'ลงทะเบียนมากกว่า 3 ราย',
    ];

    /** สรุปตัวกรองที่เลือก (ใช้เป็นหัวรายงานตอนพิมพ์/ส่งออก) เช่น ['กลุ่มสาระ: ภาพยนตร์สั้น', 'ระดับชั้น: ป.1 - ป.6'] */
    public function filterSummary(array $filters, string $type, string $level): array
    {
        $out = [];
        if (! empty($filters['subject'])) {
            $out[] = 'กลุ่มสาระ: '.(\App\Subjectgroup::find($filters['subject'])->subjectgroup_name ?? $filters['subject']);
        }
        if (! empty($filters['class'])) {
            $out[] = 'ระดับชั้น: '.(\App\Classes::find($filters['class'])->class_name ?? $filters['class']);
        }
        if ($type !== 'activities' && ! empty($filters['activity'])) {
            $out[] = 'รายการแข่งขัน: '.(\App\Activities::find($filters['activity'])->activity_name ?? $filters['activity']);
        }
        if ($level === self::LEVEL_DISTRICT && ! empty($filters['schoolgroup'])) {
            $out[] = 'เครือข่าย: '.(\App\Schoolgroup::find($filters['schoolgroup'])->schoolgroup_name ?? $filters['schoolgroup']);
        }
        if ($type === 'participants' && ! empty($filters['top3'])) {
            $out[] = 'เฉพาะอันดับ 1-3';
        }
        if ($type === 'activities' && ! empty($filters['teams'])) {
            $out[] = 'จำนวนผู้ลงทะเบียน: '.(self::TEAM_FILTERS[$filters['teams']] ?? $filters['teams']);
        }

        return $out;
    }

    public function columns(string $type, string $level): array
    {
        $cols = [
            ['key' => 'subject', 'label' => 'กลุ่มสาระ', 'width' => 18, 'kind' => 'text'],
            ['key' => 'activity', 'label' => 'รายการแข่งขัน', 'width' => 32, 'kind' => 'text'],
            ['key' => 'class', 'label' => 'ระดับชั้น', 'width' => 12, 'kind' => 'text'],
        ];
        if ($type !== 'activities') {
            $cols[] = ['key' => 'school', 'label' => 'โรงเรียน', 'width' => 28, 'kind' => 'text'];
            if ($level === self::LEVEL_DISTRICT) {
                $cols[] = ['key' => 'schoolgroup', 'label' => 'เครือข่าย', 'width' => 18, 'kind' => 'text'];
            }
        }
        if ($type === 'participants') {
            $cols[] = ['key' => 'students', 'label' => 'รายชื่อนักเรียน', 'width' => 34, 'kind' => 'list'];
            $cols[] = ['key' => 'teachers', 'label' => 'รายชื่อครู', 'width' => 30, 'kind' => 'list'];
            $cols[] = ['key' => 'score', 'label' => 'คะแนน', 'width' => 8, 'kind' => 'num'];
            $cols[] = ['key' => 'rank', 'label' => 'ลำดับที่', 'width' => 8, 'kind' => 'num'];
            $cols[] = ['key' => 'result', 'label' => 'ผล', 'width' => 26, 'kind' => 'text'];
        }
        if ($type === 'activities') {
            $cols[] = ['key' => 'teams', 'label' => 'จำนวนทีม', 'width' => 12, 'kind' => 'num'];
            $cols[] = ['key' => 'schools', 'label' => 'โรงเรียนที่ลงทะเบียน', 'width' => 50, 'kind' => 'list'];
        }

        return $cols;
    }

    /**
     * @param  array{subject?:int|null, activity?:int|null, schoolgroup?:int|null, top3?:bool}  $filters
     */
    public function rows(string $type, string $level, array $filters): Collection
    {
        $teams = $this->teams($type, $level, $filters);

        if ($type === 'activities') {
            $counts = $teams->groupBy('school_activity')->map->count();
            $schoolNames = $teams->groupBy('school_activity')->map(fn ($g) => $g->map(fn ($t) => $t->getschoolname->school_name ?? '-')->sort()->values()->all());
            $mode = (string) ($filters['teams'] ?? '');

            // ฐานเป็นรายการแข่งขันทั้งหมดของงาน เพื่อให้เห็นรายการที่ไม่มีผู้ลงทะเบียนด้วย
            return \App\Activities::query()->with(['getactivitysubject', 'getclass'])
                ->when(! empty($filters['subject']), fn ($q) => $q->where('activity_subject', (int) $filters['subject']))
                ->when(! empty($filters['class']), fn ($q) => $q->where('activity_class', (int) $filters['class']))
                ->when(! empty($filters['activity']), fn ($q) => $q->where('id', (int) $filters['activity']))
                ->get()
                ->map(fn ($activity) => [
                    'subject' => $activity->getactivitysubject?->subjectgroup_name ?? '-',
                    'activity' => $activity->activity_name ?? '-',
                    'class' => $activity->getclass?->class_name ?? '-',
                    'teams' => (int) ($counts[$activity->id] ?? 0),
                    'schools' => $schoolNames[$activity->id] ?? [],
                    'activity_id' => (int) $activity->id,
                    '_sort' => sprintf('%08d-%08d', $activity->activity_subject ?? 0, $activity->id),
                ])
                ->filter(fn ($r) => match ($mode) {
                    'none' => $r['teams'] === 0,
                    'lt3' => $r['teams'] >= 1 && $r['teams'] <= 2,
                    'eq3' => $r['teams'] === 3,
                    'gt3' => $r['teams'] > 3,
                    default => $r['teams'] > 0,
                })
                ->sortBy('_sort')->values();
        }

        return $teams
            ->sortBy(fn ($t) => sprintf('%08d-%08d-%08d-%s', $t->getactivityname->activity_subject ?? 0, $t->school_activity,
                $type === 'participants' ? $this->sortRank($t) : 0, $t->getschoolname->school_name ?? ''))
            ->values()
            ->map(fn ($t) => $type === 'participants' ? $this->participantRow($t) : $this->schoolRow($t));
    }

    private function teams(string $type, string $level, array $filters): Collection
    {
        $model = $level === self::LEVEL_NETWORK ? addschools_areas::class : addschool::class;
        $with = ['getactivityname.getactivitysubject', 'getactivityname.getclass'];
        $with[] = 'getschoolname';
        if ($type !== 'activities') {
            $with[] = 'getschoolgroup';
        }
        if ($type === 'participants') {
            $with['getstudentname'] = fn ($q) => $q->with('getstudenttitle')->orderBy('type')->orderBy('id');
        }

        $query = $model::query()->with($with);

        if (! empty($filters['schoolgroup'])) {
            $query->where('school_schoolgroup', (int) $filters['schoolgroup']);
        }
        if (! empty($filters['activity'])) {
            $query->where('school_activity', (int) $filters['activity']);
        }
        if (! empty($filters['subject'])) {
            $query->whereHas('getactivityname', fn ($q) => $q->where('activity_subject', (int) $filters['subject']));
        }
        if (! empty($filters['class'])) {
            $query->whereHas('getactivityname', fn ($q) => $q->where('activity_class', (int) $filters['class']));
        }
        if ($type === 'participants' && ! empty($filters['top3'])) {
            $query->where('activity_status', 1)->whereBetween('activity_rank', [1, 3])->where('student_score', '>', 0);
        }

        return $query->get();
    }

    private function sortRank($team): int
    {
        $rank = (int) $team->activity_rank;

        return ((int) $team->activity_status === 1 && $rank > 0) ? $rank : 9999;
    }

    private function schoolRow($team): array
    {
        $activity = $team->getactivityname;

        return [
            'subject' => $activity?->getactivitysubject?->subjectgroup_name ?? '-',
            'activity' => $activity->activity_name ?? '-',
            'class' => $activity?->getclass?->class_name ?? '-',
            'school' => $team->getschoolname->school_name ?? '-',
            'schoolgroup' => $team->getschoolgroup->schoolgroup_name ?? '-',
            'activity_id' => (int) $team->school_activity,
        ];
    }

    private function participantRow($team): array
    {
        $people = $team->getstudentname ?? collect();
        $names = function ($type) use ($people) {
            return $people->where('type', $type)->values()->map(fn ($p, $i) => ($i + 1).'. '
                .trim(($p->getstudenttitle->title ?? '').$p->name.' '.$p->lastname))->all();
        };

        $status = (int) $team->activity_status;
        if ($status === 98) {
            $result = 'งดมอบเกียรติบัตร';
        } elseif ($status === 99) {
            $result = 'เข้าร่วม (ไม่มีการแข่งขัน)';
        } elseif ($status === 1 && (float) $team->student_score > 0) {
            [$medal, $rank] = CertificateText::award($team);
            $result = trim($medal.' '.$rank);
        } elseif ($status === 1) {
            $result = 'ไม่เข้าร่วม';
        } else {
            $result = '-';
        }

        return $this->schoolRow($team) + [
            'students' => $names(0),
            'teachers' => $names(1),
            'score' => ($status === 1) ? (float) $team->student_score : null,
            'rank' => ($status === 1 && (int) $team->activity_rank > 0) ? (int) $team->activity_rank : null,
            'result' => $result,
        ];
    }
}
