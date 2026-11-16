<?php

namespace App\Http\Controllers;

use App\Branch;
use App\CompetitionAccessRule;
use App\School;
use App\Schoolgroup;
use App\Services\CompetitionAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemactiveController extends Controller
{
    private const ALLOWED = [
        'network|master|all',
        'network|network|all', 'network|network|network',
        'network|school|all', 'network|school|network', 'network|school|school',
        'district|network|all', 'district|network|network',
        'district|center|all', 'district|center|center',
    ];

    public function index(CompetitionAccessService $access)
    {
        return view('systemactive.add', [
            'rules' => $access->rules((int) active_event_id()),
            'schoolgroups' => Schoolgroup::orderBy('schoolgroup_name')->get(),
            'schools' => School::orderBy('school_group')->orderBy('school_name')->get(),
            'centers' => Branch::orderBy('branch_name')->get(),
        ]);
    }

    public function updateRule(Request $request)
    {
        $data = $request->validate([
            'competition_level' => 'required|in:network,district',
            'subject_type' => 'required|in:master,school,network,center',
            'scope_type' => 'required|in:all,network,school,center',
            'scope_id' => 'nullable|integer|min:0',
            'is_closed' => 'required|boolean',
        ]);
        $data['scope_id'] = $data['scope_type'] === 'all' ? 0 : (int) ($data['scope_id'] ?? 0);
        $this->validateCombination($data);
        $this->validateTarget($data);
        $this->assertParentsOpenWhenOpening($data);

        $targets = $this->cascadeTargets($data);
        DB::transaction(function () use ($request, $data, $targets) {
            foreach ($targets as $target) {
                $keys = $target + ['event_id' => (int) active_event_id()];
                $rule = CompetitionAccessRule::firstOrNew($keys);
                $before = $rule->exists ? $rule->toArray() : null;
                $rule->fill(['is_closed' => (bool) $data['is_closed'], 'message' => null,
                    'changed_by' => $request->user()->id])->save();
                DB::table('competition_access_rule_logs')->insert([
                    'event_id' => (int) active_event_id(), 'rule_id' => $rule->id,
                    'user_id' => $request->user()->id, 'ip_address' => $request->ip(),
                    'before' => $before ? json_encode($before, JSON_UNESCAPED_UNICODE) : null,
                    'after' => json_encode($rule->fresh()->toArray(), JSON_UNESCAPED_UNICODE),
                    'created_at' => now(),
                ]);
            }
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'บันทึกสถานะเรียบร้อยแล้ว',
                'is_closed' => (bool) $data['is_closed'],
                'affected' => array_map(fn ($target) => $target + [
                    'is_closed' => (bool) $data['is_closed'],
                ], $targets),
            ]);
        }

        return back()->with('success', 'บันทึกสถานะการเข้าถึงเรียบร้อยแล้ว');
    }

    public function networkSchoolUpdate(Request $request)
    {
        $request->merge(['competition_level' => 'network', 'subject_type' => 'school']);
        if ((int) $request->user()->position === 5) {
            $scope = $request->input('scope_type');
            $scopeId = (int) $request->input('scope_id');
            $ownsTarget = ($scope === 'network' && $scopeId === (int) $request->user()->schoolgroup)
                || ($scope === 'school' && School::whereKey($scopeId)
                    ->where('school_group', $request->user()->schoolgroup)->exists());
            abort_unless($ownsTarget, 403, 'จัดการได้เฉพาะโรงเรียนในเครือข่ายของตนเอง');

            $masterClosed = CompetitionAccessRule::where('event_id', active_event_id())
                ->where('competition_level', 'network')->where('subject_type', 'master')
                ->where('scope_type', 'all')->where('scope_id', 0)->where('is_closed', true)->exists();
            abort_if($masterClosed, 423, 'แอดมินหลัก Freeze ระบบระดับเครือข่ายแล้ว');
        }

        return $this->updateRule($request);
    }

    private function validateCombination(array $data): void
    {
        $key = implode('|', [$data['competition_level'], $data['subject_type'], $data['scope_type']]);
        abort_unless(in_array($key, self::ALLOWED, true), 422, 'รูปแบบการควบคุมระบบไม่ถูกต้อง');
        abort_if($data['scope_type'] !== 'all' && $data['scope_id'] < 1, 422, 'กรุณาเลือกรายการที่ต้องการควบคุม');
    }

    private function validateTarget(array $data): void
    {
        $exists = match ($data['scope_type']) {
            'network' => Schoolgroup::whereKey($data['scope_id'])->exists(),
            'school' => School::whereKey($data['scope_id'])->exists(),
            'center' => Branch::whereKey($data['scope_id'])->exists(),
            default => true,
        };
        abort_unless($exists, 422, 'ไม่พบรายการที่เลือก');
    }

    private function cascadeTargets(array $data): array
    {
        $target = fn ($level, $subject, $scope, $id = 0) => [
            'competition_level' => $level, 'subject_type' => $subject,
            'scope_type' => $scope, 'scope_id' => (int) $id,
        ];
        $targets = [$target($data['competition_level'], $data['subject_type'], $data['scope_type'], $data['scope_id'])];

        if ($data['competition_level'] === 'network' && $data['subject_type'] === 'master') {
            $targets[] = $target('network', 'network', 'all');
            $targets[] = $target('network', 'school', 'all');
            foreach (Schoolgroup::pluck('id') as $groupId) {
                $targets[] = $target('network', 'network', 'network', $groupId);
                $targets[] = $target('network', 'school', 'network', $groupId);
            }
            foreach (School::pluck('id') as $schoolId) {
                $targets[] = $target('network', 'school', 'school', $schoolId);
            }
        } elseif ($data['competition_level'] === 'network' && $data['subject_type'] === 'network' && $data['scope_type'] === 'all') {
            foreach (Schoolgroup::pluck('id') as $groupId) $targets[] = $target('network', 'network', 'network', $groupId);
        } elseif ($data['competition_level'] === 'network' && $data['subject_type'] === 'school' && $data['scope_type'] === 'all') {
            foreach (Schoolgroup::pluck('id') as $groupId) $targets[] = $target('network', 'school', 'network', $groupId);
            foreach (School::pluck('id') as $schoolId) $targets[] = $target('network', 'school', 'school', $schoolId);
        } elseif ($data['competition_level'] === 'network' && $data['subject_type'] === 'school' && $data['scope_type'] === 'network') {
            foreach (School::where('school_group', $data['scope_id'])->pluck('id') as $schoolId) $targets[] = $target('network', 'school', 'school', $schoolId);
        } elseif ($data['competition_level'] === 'district' && $data['subject_type'] === 'network' && $data['scope_type'] === 'all') {
            foreach (Schoolgroup::pluck('id') as $groupId) $targets[] = $target('district', 'network', 'network', $groupId);
        } elseif ($data['competition_level'] === 'district' && $data['subject_type'] === 'center' && $data['scope_type'] === 'all') {
            foreach (Branch::pluck('id') as $centerId) $targets[] = $target('district', 'center', 'center', $centerId);
        }

        return collect($targets)->unique(fn ($item) => implode('|', $item))->values()->all();
    }

    private function assertParentsOpenWhenOpening(array $data): void
    {
        if ((bool) $data['is_closed']) return;
        $parents = [];
        if ($data['competition_level'] === 'network' && $data['subject_type'] !== 'master') {
            $parents[] = ['network', 'master', 'all', 0];
        }
        if ($data['scope_type'] !== 'all') {
            $parents[] = [$data['competition_level'], $data['subject_type'], 'all', 0];
        }
        if ($data['competition_level'] === 'network' && $data['subject_type'] === 'school' && $data['scope_type'] === 'school') {
            $groupId = (int) School::whereKey($data['scope_id'])->value('school_group');
            $parents[] = ['network', 'school', 'network', $groupId];
        }
        foreach ($parents as [$level, $subject, $scope, $id]) {
            $closed = CompetitionAccessRule::where('event_id', active_event_id())
                ->where('competition_level', $level)->where('subject_type', $subject)
                ->where('scope_type', $scope)->where('scope_id', $id)->where('is_closed', true)->exists();
            abort_if($closed, 423, 'ต้องเปิดหัวกลุ่มก่อน จึงจะเปลี่ยนสถานะลูกกลุ่มได้');
        }
    }
}
