<?php

namespace Tests\Feature;

use App\Services\NoticeViewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NoticeViewStatsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }

    private function req(string $ua, string $ip = '10.0.0.1'): Request
    {
        return Request::create('/x', 'GET', [], [], [], ['HTTP_USER_AGENT' => $ua, 'REMOTE_ADDR' => $ip]);
    }

    public function test_counts_dedups_and_skips_bots(): void
    {
        $id = 999001;
        $this->assertTrue(NoticeViewService::record($this->req('Mozilla/5.0 Chrome'), $id));
        $this->assertFalse(NoticeViewService::record($this->req('Mozilla/5.0 Chrome'), $id), 'refresh within 30 min');
        $this->assertTrue(NoticeViewService::record($this->req('Mozilla/5.0 Chrome', '10.0.0.2'), $id), 'other visitor');
        $this->assertFalse(NoticeViewService::record($this->req('Googlebot/2.1'), $id), 'bot');

        $s = NoticeViewService::stats($id);
        $this->assertSame(2, $s['total']);
        $this->assertSame(2, $s['unique']);
        $this->assertSame(2, $s['today']);
        $this->assertCount(14, $s['daily']);
        $this->assertSame([$id => 2], NoticeViewService::totals([$id]));
    }

    public function test_counts_again_after_window(): void
    {
        $id = 999002;
        NoticeViewService::record($this->req('Mozilla/5.0'), $id);
        DB::table('notice_views')->where('notice_id', $id)->update(['viewed_at' => now()->subMinutes(31)]);
        $this->assertTrue(NoticeViewService::record($this->req('Mozilla/5.0'), $id));
        $this->assertSame(2, NoticeViewService::stats($id)['total']);
    }
}
