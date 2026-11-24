<?php

namespace Tests\Feature;

use App\Jows;
use App\Jows_areas;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * กรรมการต้องแยกตามงาน/เครือข่าย/ระดับ แต่ชื่อซ้ำกันได้ (การแข่งขันจัดไม่พร้อมกัน)
 */
class JudgeScopeTest extends TestCase
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

    private function row(int $event, int $area, string $name): int
    {
        return DB::table('jows_areas')->insertGetId([
            'event_id' => $event, 'year' => '9999', 'jow_title' => 1, 'jow_name' => $name, 'jow_lastname' => 'ทดสอบ',
            'jow_activity' => null, 'jow_type' => 0, 'jowclass' => 'กรรมการ', 'jow_area' => $area,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function test_judges_are_separated_by_event_and_network_but_same_name_is_allowed(): void
    {
        $events = DB::table('events')->orderBy('id')->pluck('id');
        $numbers = DB::table('events')->orderBy('id')->pluck('event_number', 'id');
        $this->assertGreaterThanOrEqual(2, $events->count());
        [$e1, $e2] = [$events[0], $events[1]];

        $a = $this->row($e2, 901, 'ชื่อซ้ำ');
        $b = $this->row($e2, 902, 'ชื่อซ้ำ');   // คนชื่อเดียวกันอยู่คนละเครือข่าย
        $c = $this->row($e1, 901, 'ชื่อซ้ำ');   // และคนละงาน

        config(['app.event_number' => $numbers[$e2]]);
        $this->assertSame([$a], Jows_areas::where('jow_area', 901)->pluck('id')->all());
        $this->assertSame([$b], Jows_areas::where('jow_area', 902)->pluck('id')->all());

        config(['app.event_number' => $numbers[$e1]]);
        $this->assertSame([$c], Jows_areas::where('jow_area', 901)->pluck('id')->all());
        $this->assertSame([], Jows_areas::where('jow_area', 902)->pluck('id')->all());
    }

    public function test_district_level_judges_are_a_separate_table_from_network_level(): void
    {
        $event = DB::table('events')->orderByDesc('id')->value('id');
        config(['app.event_number' => DB::table('events')->where('id', $event)->value('event_number')]);
        $this->row($event, 903, 'เฉพาะระดับเครือข่าย');

        $this->assertSame(0, Jows::where('jow_name', 'เฉพาะระดับเครือข่าย')->count());
        $this->assertSame(1, Jows_areas::where('jow_name', 'เฉพาะระดับเครือข่าย')->count());
    }

    public function test_every_judge_row_has_event_and_network(): void
    {
        $this->assertSame(0, DB::table('jows_areas')->where(fn ($q) => $q->whereNull('event_id')->orWhereNull('jow_area'))->count());
        $this->assertSame(0, DB::table('jows')->whereNull('event_id')->count());
    }
}
