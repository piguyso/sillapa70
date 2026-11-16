<?php

namespace Tests\Unit;

use App\CompetitionAccessRule;
use App\Services\CompetitionAccessService;
use App\User;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class CompetitionAccessServiceTest extends TestCase
{
    public function test_master_freeze_blocks_network_admin(): void
    {
        $user = new User(['position' => 5]);
        $user->position = 5;
        $user->schoolgroup = 2;
        $rules = new Collection([$this->rule('network', 'master', 'all', 0)]);

        $this->assertNotNull((new CompetitionAccessService)->blockingRule($user, 'network', $rules));
    }

    public function test_school_rule_only_blocks_target_school(): void
    {
        $rules = new Collection([$this->rule('network', 'school', 'school', 10)]);
        $target = new User; $target->position = 0; $target->schoolgroup = 2; $target->school_id = 10;
        $other = new User; $other->position = 0; $other->schoolgroup = 2; $other->school_id = 11;

        $service = new CompetitionAccessService;
        $this->assertNotNull($service->blockingRule($target, 'network', $rules));
        $this->assertNull($service->blockingRule($other, 'network', $rules));
    }

    public function test_center_rule_only_blocks_target_center(): void
    {
        $rules = new Collection([$this->rule('district', 'center', 'center', 7)]);
        $target = new User; $target->position = 1; $target->branch = 7;
        $other = new User; $other->position = 1; $other->branch = 8;

        $service = new CompetitionAccessService;
        $this->assertNotNull($service->blockingRule($target, 'district', $rules));
        $this->assertNull($service->blockingRule($other, 'district', $rules));
    }

    public function test_system_admin_always_bypasses_rules(): void
    {
        $admin = new User; $admin->position = 10;
        $rules = new Collection([$this->rule('district', 'center', 'all', 0)]);
        $this->assertNull((new CompetitionAccessService)->blockingRule($admin, 'district', $rules));
    }

    private function rule(string $level, string $subject, string $scope, int $id): CompetitionAccessRule
    {
        return new CompetitionAccessRule([
            'competition_level' => $level, 'subject_type' => $subject,
            'scope_type' => $scope, 'scope_id' => $id, 'is_closed' => true,
        ]);
    }
}
