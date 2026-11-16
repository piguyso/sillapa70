<?php

namespace Tests\Feature;

use App\Event;
use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CompetitionStructureTransferTest extends TestCase
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

    public function test_transfer_copies_the_complete_structure_and_remaps_relations(): void
    {
        $target = Event::where('is_active', 1)->firstOrFail();
        $source = Event::where('id', '!=', $target->id)->orderByDesc('event_number')->firstOrFail();
        $admin = User::where('position', 10)->firstOrFail();
        $sourceActivityCount = DB::table('activities')->where('event_id', $source->id)->count();

        $this->actingAs($admin)
            ->post('/'.$target->event_number.'/config/activities/copy', [
                'source_event_id' => $source->id,
                'clear_existing' => '1',
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'activities_count' => $sourceActivityCount,
            ]);

        $this->assertSame($sourceActivityCount, DB::table('activities')->where('event_id', $target->id)->count());
        $this->assertSame(0, DB::table('activities as a')
            ->join('branches as b', 'b.id', '=', 'a.activity_branch')
            ->where('a.event_id', $target->id)
            ->whereColumn('a.event_id', '!=', 'b.event_id')->count());
        $this->assertSame(0, DB::table('activities as a')
            ->join('subjectgroups as s', 's.id', '=', 'a.activity_subject')
            ->where('a.event_id', $target->id)
            ->whereColumn('a.event_id', '!=', 's.event_id')->count());
        $this->assertSame($sourceActivityCount, DB::table('activities')->where('event_id', $source->id)->count());
    }
}
