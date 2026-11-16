<?php

namespace Tests\Feature;

use App\Event;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetLifecycleTest extends TestCase
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

    public function test_admin_can_reset_one_school_and_user_must_change_password(): void
    {
        $event = Event::orderByDesc('is_active')->firstOrFail();
        $admin = User::where('position', 10)->firstOrFail();
        $schoolUser = User::where('position', 0)->whereNotNull('school_id')->firstOrFail();

        $this->actingAs($admin)
            ->get('/'.$event->event_number.'/myuser')
            ->assertOk()
            ->assertSee('Reset รหัสผ่านผู้ใช้งาน');

        $this->actingAs($admin)
            ->post('/'.$event->event_number.'/myuser/password-reset', [
                'target_type' => 'school',
                'target_id' => $schoolUser->school_id,
            ])
            ->assertRedirect();

        $schoolUser->refresh();
        $this->assertTrue((bool) $schoolUser->must_change_password);
        $this->assertTrue(Hash::check($schoolUser->email, $schoolUser->password));

        $this->actingAs($schoolUser)
            ->get('/'.$event->event_number.'/')
            ->assertRedirect('/'.$event->event_number.'/password/change-required');

        $this->actingAs($schoolUser)
            ->put('/'.$event->event_number.'/password/change-required', [
                'username' => $schoolUser->email,
                'password' => 'NewSecure987!',
                'password_confirmation' => 'NewSecure987!',
            ])
            ->assertRedirect();

        $schoolUser->refresh();
        $this->assertFalse((bool) $schoolUser->must_change_password);
        $this->assertTrue(Hash::check('NewSecure987!', $schoolUser->password));
    }

    public function test_non_admin_cannot_reset_passwords(): void
    {
        $event = Event::orderByDesc('is_active')->firstOrFail();
        $schoolUser = User::where('position', 0)->firstOrFail();

        $this->actingAs($schoolUser)
            ->post('/'.$event->event_number.'/myuser/password-reset', [
                'target_type' => 'school_all',
            ])
            ->assertForbidden();
    }
}
