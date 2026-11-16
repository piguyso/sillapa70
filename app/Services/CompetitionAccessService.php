<?php

namespace App\Services;

use App\CompetitionAccessRule;
use App\User;
use Illuminate\Support\Collection;

class CompetitionAccessService
{
    public function rules(int $eventId): Collection
    {
        return CompetitionAccessRule::where('event_id', $eventId)->get();
    }

    public function blockingRule(User $user, string $level, ?Collection $rules = null): ?CompetitionAccessRule
    {
        if ((int) $user->position >= 10) {
            return null;
        }

        $rules = $rules ?: $this->rules((int) active_event_id());
        $checks = [];

        if ($level === 'network') {
            $checks[] = ['master', 'all', 0];
            if ((int) $user->position === 0) {
                $checks[] = ['school', 'all', 0];
                $checks[] = ['school', 'network', (int) $user->schoolgroup];
                $checks[] = ['school', 'school', (int) $user->school_id];
            } elseif ((int) $user->position === 5) {
                $checks[] = ['network', 'all', 0];
                $checks[] = ['network', 'network', (int) $user->schoolgroup];
            }
        } elseif ($level === 'district') {
            if ((int) $user->position === 5) {
                $checks[] = ['network', 'all', 0];
                $checks[] = ['network', 'network', (int) $user->schoolgroup];
            } elseif ((int) $user->position === 1) {
                $checks[] = ['center', 'all', 0];
                $checks[] = ['center', 'center', (int) $user->branch];
            }
        }

        foreach ($checks as [$subject, $scope, $id]) {
            $match = $rules->first(fn ($rule) =>
                $rule->competition_level === $level
                && $rule->subject_type === $subject
                && $rule->scope_type === $scope
                && (int) $rule->scope_id === $id
                && $rule->is_closed
            );
            if ($match) {
                return $match;
            }
        }

        return null;
    }
}
