<?php

namespace App\Services;

use App\Activities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompetitionScoreService
{
    public function process(
        string $teamModel,
        int $activityId,
        array $submittedScores,
        int $action,
        ?int $schoolgroupId = null,
        bool $updateActivityStatus = false,
        bool $allowNoContest = false,
    ): void {
        if (! in_array($action, $allowNoContest ? [0, 1, 2, 98, 99] : [0, 1, 2], true)) {
            throw ValidationException::withMessages(['activity_chk' => 'สถานะผลการแข่งขันไม่ถูกต้อง']);
        }

        /** @var Model $model */
        $model = new $teamModel;
        $query = $model->newQuery()->where('school_activity', $activityId);
        if ($schoolgroupId !== null) {
            $query->where('school_schoolgroup', $schoolgroupId);
        }

        $teams = $query->get();
        if ($teams->isEmpty()) {
            throw ValidationException::withMessages(['activity_score' => 'ไม่พบทีมในรายการแข่งขันนี้']);
        }

        $scores = [];
        if (in_array($action, [0, 1], true)) {
            foreach ($teams as $team) {
                $value = $submittedScores[$team->getKey()] ?? null;
                if (! is_numeric($value) || (float) $value < 0 || (float) $value > 100) {
                    throw ValidationException::withMessages([
                        "activity_score.{$team->getKey()}" => 'คะแนนต้องเป็นตัวเลขระหว่าง 0 ถึง 100',
                    ]);
                }
                $scores[$team->getKey()] = (float) $value;
            }

            $unexpectedIds = array_diff(array_map('intval', array_keys($submittedScores)), $teams->modelKeys());
            if ($unexpectedIds !== []) {
                throw ValidationException::withMessages(['activity_score' => 'พบทีมที่ไม่ได้อยู่ในรายการแข่งขันนี้']);
            }

            $rankedScores = array_filter($scores, fn (float $score): bool => $score > 0);
            $scoreCounts = array_count_values(array_map(function (float $score): string {
                $formatted = rtrim(rtrim(number_format($score, 10, '.', ''), '0'), '.');

                return $formatted === '' ? '0' : $formatted;
            }, $rankedScores));
            $duplicateScores = array_keys(array_filter($scoreCounts, fn (int $count): bool => $count > 1));
            if ($duplicateScores !== []) {
                throw ValidationException::withMessages([
                    'activity_score' => 'ไม่อนุญาตให้บันทึกคะแนนเท่ากัน กรุณาแก้คะแนนที่ซ้ำ: '.implode(', ', $duplicateScores),
                ]);
            }

            arsort($scores, SORT_NUMERIC);
        }

        DB::transaction(function () use ($teams, $scores, $action, $updateActivityStatus, $activityId): void {
            if ($action === 2) {
                foreach ($teams as $team) {
                    $team->forceFill(['student_score' => 0, 'activity_rank' => 0, 'activity_status' => 0])->save();
                }
            } elseif (in_array($action, [98, 99], true)) {
                foreach ($teams as $team) {
                    $team->forceFill(['student_score' => 80, 'activity_rank' => $action, 'activity_status' => $action])->save();
                }
            } else {
                $position = 0;
                $rank = 0;
                $previousScore = null;
                foreach ($scores as $teamId => $score) {
                    $position++;
                    if ($previousScore === null || $score !== $previousScore) {
                        $rank = $position;
                    }
                    $team = $teams->firstWhere($teams->first()->getKeyName(), $teamId);
                    $team->forceFill([
                        'student_score' => $score,
                        'activity_rank' => $score > 0 ? $rank : 0,
                        'activity_status' => $action === 1 ? 1 : 0,
                    ])->save();
                    $previousScore = $score;
                }
            }

            if ($updateActivityStatus) {
                Activities::whereKey($activityId)->update(['activity_status' => $action === 1 ? 1 : 0]);
            }
        });
    }
}
