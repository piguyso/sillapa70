<?php

namespace App\Http\Controllers\Concerns;

use App\addschools_areas;
use App\addstudents_areas;
use App\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait AuthorizesNetworkRegistration
{
    private function registrationGroupId(): int
    {
        return (int) (request()->attributes->get('schoolgroup_id') ?: Auth::user()?->schoolgroup);
    }

    private function authorizedRegistrationSchool(int $schoolId): School
    {
        $query = School::whereKey($schoolId)->where('school_group', $this->registrationGroupId());
        if ((int) Auth::user()->position === 0) {
            $query->whereKey((int) Auth::user()->school_id);
        }

        $school = $query->first();
        if (! $school) {
            $this->logDeniedNetworkAccess('school', $schoolId);
            abort(404);
        }

        return $school;
    }

    private function authorizedRegistrationTeam(int $teamId): addschools_areas
    {
        $query = addschools_areas::whereKey($teamId)
            ->where('school_schoolgroup', $this->registrationGroupId());
        if ((int) Auth::user()->position === 0) {
            $query->where('school', (int) Auth::user()->school_id);
        }

        $team = $query->first();
        if (! $team) {
            $this->logDeniedNetworkAccess('team', $teamId);
            abort(404);
        }

        return $team;
    }

    private function authorizedRegistrationPerson(int $personId): addstudents_areas
    {
        $person = addstudents_areas::find($personId);
        if (! $person) {
            $this->logDeniedNetworkAccess('person', $personId);
            abort(404);
        }
        $this->authorizedRegistrationTeam((int) $person->school);

        return $person;
    }

    private function logDeniedNetworkAccess(string $resource, int $id): void
    {
        Log::warning('Denied cross-school network data access', [
            'user_id' => Auth::id(),
            'ip' => request()->ip(),
            'resource' => $resource,
            'resource_id' => $id,
            'school_id' => Auth::user()?->school_id,
            'schoolgroup_id' => $this->registrationGroupId(),
        ]);
    }
}
