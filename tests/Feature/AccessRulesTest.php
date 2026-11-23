<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * กฎสิทธิ์ที่ห้ามพัง (characterization test) — รันบนฐานข้อมูลจริงใน transaction แล้ว rollback
 */
class AccessRulesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') === 'sqlite' && ! in_array('sqlite', \PDO::getAvailableDrivers(), true)) {
            $this->markTestSkipped('PDO SQLite is not installed in this environment.');
        }
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }

    private function eventNumber(): int
    {
        return (int) DB::table('events')->where('is_active', 1)->value('event_number');
    }

    public function test_guest_cannot_open_user_management(): void
    {
        $this->get('/'.$this->eventNumber().'/myuser')->assertRedirect();
    }

    public function test_non_admin_cannot_open_user_management(): void
    {
        foreach ([0, 1, 5] as $position) {
            $user = User::where('position', $position)->first();
            if (! $user) {
                continue;
            }
            $status = $this->actingAs($user)->get('/'.$this->eventNumber().'/myuser')->getStatusCode();
            $this->assertNotSame(200, $status, "position {$position} must not reach /myuser");
        }
    }

    public function test_admin_can_open_user_management(): void
    {
        $admin = User::where('position', 10)->firstOrFail();
        $this->actingAs($admin)->get('/'.$this->eventNumber().'/myuser')->assertOk();
    }

    public function test_one_account_per_school_and_per_network(): void
    {
        $this->assertSame(0, DB::table('users')->where('position', 0)->whereNotNull('school_id')
            ->groupBy('school_id')->havingRaw('count(*) > 1')->select('school_id')->get()->count());
        $this->assertSame(0, DB::table('users')->where('position', 5)
            ->groupBy('schoolgroup')->havingRaw('count(*) > 1')->select('schoolgroup')->get()->count());
    }

    public function test_school_user_group_matches_school_record(): void
    {
        $bad = DB::table('users as u')->join('schools as s', 's.id', '=', 'u.school_id')
            ->where('u.position', 0)->whereRaw('NOT (u.schoolgroup <=> s.school_group)')->count();
        $this->assertSame(0, $bad, 'users.schoolgroup must equal schools.school_group for school accounts');
    }

    public function test_usernames_are_unique_and_positions_known(): void
    {
        $this->assertSame(0, DB::table('users')->groupBy('email')->havingRaw('count(*) > 1')->select('email')->get()->count());
        $this->assertSame(0, DB::table('users')->whereNotIn('position', [0, 1, 5, 10])->count());
    }

    public function test_register_blocks_second_account_for_same_school(): void
    {
        $admin = User::where('position', 10)->firstOrFail();
        $taken = User::where('position', 0)->whereNotNull('school_id')->firstOrFail();

        $this->actingAs($admin)->post('/'.$this->eventNumber().'/myuser', [
            'account_type' => 'school', 'target_id' => $taken->school_id,
            'name' => 'x', 'lastname' => 'y', 'email' => 'dup-test-'.uniqid(),
        ])->assertSessionHasErrors();

        $this->assertSame(1, DB::table('users')->where('position', 0)->where('school_id', $taken->school_id)->count());
    }
}
