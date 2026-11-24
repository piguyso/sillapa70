<?php

namespace Tests\Feature;

use App\AuditLog;
use App\School;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** ตรวจว่าระบบบันทึก "ใครทำอะไร" ครบ และไม่เก็บรหัสผ่าน */
class AuditLogTest extends TestCase
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

    public function test_model_create_update_delete_are_logged_with_before_after(): void
    {
        $admin = User::where('position', 10)->firstOrFail();
        $this->actingAs($admin);

        $school = new School();
        $school->school_name = 'ทดสอบ audit';
        $school->school_detail = 'x';
        $school->school_group = 1;
        $school->save();
        $school->school_detail = 'y';
        $school->save();
        $id = $school->id;
        $school->delete();

        $rows = AuditLog::where('subject_table', 'schools')->where('subject_id', $id)->orderBy('id')->get();
        $this->assertSame(['created', 'updated', 'deleted'], $rows->pluck('action')->all());
        $this->assertSame($admin->email, $rows[0]->username);
        $changes = json_decode($rows[1]->changes, true);
        $this->assertSame('x', $changes['before']['school_detail']);
        $this->assertSame('y', $changes['after']['school_detail']);
    }

    public function test_password_values_are_never_stored(): void
    {
        $admin = User::where('position', 10)->firstOrFail();
        $this->actingAs($admin);

        $user = User::where('position', 0)->firstOrFail();
        $user->password = bcrypt('secret-new');
        $user->save();

        $log = AuditLog::where('subject_table', 'users')->where('subject_id', $user->id)->where('action', 'updated')->latest('id')->firstOrFail();
        $this->assertStringNotContainsString('secret-new', $log->changes);
        $this->assertStringNotContainsString('$2y$', $log->changes);
        $this->assertSame('***', json_decode($log->changes, true)['after']['password']);
    }

    public function test_login_logout_and_failed_login_are_logged(): void
    {
        $user = User::where('position', 0)->firstOrFail();
        Auth::login($user);
        Auth::logout();
        event(new \Illuminate\Auth\Events\Failed('web', null, ['email' => 'nobody-x']));

        $this->assertTrue(AuditLog::where('action', 'login')->where('user_id', $user->id)->exists());
        $this->assertTrue(AuditLog::where('action', 'logout')->exists());
        $this->assertTrue(AuditLog::where('action', 'login_failed')->where('username', 'nobody-x')->exists());
    }

    public function test_write_requests_are_logged_without_field_values(): void
    {
        $admin = User::where('position', 10)->firstOrFail();
        $this->actingAs($admin)->post('/'.$this->eventNumber().'/school-import/purge', ['confirm_text' => 'ผิด', 'secret_field' => 'TOPSECRET']);

        $log = AuditLog::where('action', 'request')->where('description', 'like', 'POST %school-import/purge%')->latest('id')->firstOrFail();
        $this->assertSame($admin->email, $log->username);
        $this->assertStringNotContainsString('TOPSECRET', (string) $log->changes);
        $this->assertContains('secret_field', json_decode($log->changes, true)['fields']);
    }

    public function test_only_admin_can_view_audit_page_and_filters_work(): void
    {
        $this->get('/'.$this->eventNumber().'/audit-logs')->assertRedirect();

        $school = User::where('position', 0)->firstOrFail();
        $status = $this->actingAs($school)->get('/'.$this->eventNumber().'/audit-logs')->getStatusCode();
        $this->assertNotSame(200, $status);

        $admin = User::where('position', 10)->firstOrFail();
        AuditLog::create(['action' => 'updated', 'username' => 'marker-user', 'subject_table' => 'schools', 'description' => 'marker-desc', 'event_id' => active_event_id()]);

        $this->actingAs($admin)->get('/'.$this->eventNumber().'/audit-logs?user=marker-user&action=updated')
            ->assertOk()->assertSee('marker-desc');
        $this->actingAs($admin)->get('/'.$this->eventNumber().'/audit-logs?user=marker-user&action=deleted')
            ->assertOk()->assertDontSee('marker-desc');
    }
}
