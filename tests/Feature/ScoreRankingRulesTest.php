<?php

namespace Tests\Feature;

use App\addschool;
use App\addschools_areas;
use App\Services\CompetitionScoreService;
use App\Services\TransferReadinessService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/** กติกาจัดอันดับ: ระดับเขตห้ามซ้ำลำดับ 1-4; ระดับเครือข่ายห้ามซ้ำลำดับ 1 ถึง (จำนวนตัวแทน + 1) */
class ScoreRankingRulesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') === 'sqlite' && ! in_array('sqlite', \PDO::getAvailableDrivers(), true)) {
            $this->markTestSkipped('PDO SQLite is not installed in this environment.');
        }
        config(['app.event_number' => (int) DB::table('events')->where('id', 1)->value('event_number')]);
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }

    /** @return array{0:int,1:int[]} activity id และ team ids ของรายการระดับเขตที่มีทีมมากพอ */
    private function districtTeams(): array
    {
        $act = DB::table('addschools')->where('event_id', 1)->select('school_activity', DB::raw('count(*) c'))
            ->groupBy('school_activity')->having('c', '>=', 7)->first();
        $this->assertNotNull($act, 'ไม่พบรายการที่มีทีมอย่างน้อย 7 ทีม');

        return [(int) $act->school_activity, DB::table('addschools')->where('event_id', 1)->where('school_activity', $act->school_activity)->pluck('id')->all()];
    }

    private function scores(array $ids, array $head): array
    {
        $out = [];
        foreach ($ids as $i => $id) {
            $out[$id] = $head[$i] ?? (30 - $i); // ที่เหลือคะแนนไม่ซ้ำ
        }

        return $out;
    }

    private function save(array $scores, int $activity, ?int $noTieThrough = null): ?string
    {
        try {
            $args = [addschool::class, $activity, $scores, 0];
            if ($noTieThrough !== null) {
                (new CompetitionScoreService)->process(...$args, schoolgroupId: null, updateActivityStatus: false, allowNoContest: false, noTieThrough: $noTieThrough);
            } else {
                (new CompetitionScoreService)->process(...$args);
            }

            return null;
        } catch (ValidationException $e) {
            return collect($e->errors())->flatten()->first();
        }
    }

    public function test_district_default_forbids_ties_in_ranks_1_to_4_only(): void
    {
        [$act, $ids] = $this->districtTeams();

        $this->assertNotNull($this->save($this->scores($ids, [90, 80, 70, 70]), $act));
        $this->assertNotNull($this->save($this->scores($ids, [90, 80, 70, 60, 60]), $act)); // 4 กับ 5 ซ้ำ
        $this->assertNull($this->save($this->scores($ids, [90, 80, 70, 65, 60, 60]), $act));  // 5 กับ 6 ซ้ำได้
        $this->assertSame([1, 2, 3, 4, 5, 5, 7], array_slice(
            DB::table('addschools')->whereIn('id', $ids)->orderByDesc('student_score')->pluck('activity_rank')->all(), 0, 7));
    }

    public function test_network_rule_follows_representative_count(): void
    {
        [$act, $ids] = $this->districtTeams();

        // ตัวแทน 2 ทีม -> ห้ามซ้ำลำดับ 1-3
        $this->assertNotNull($this->save($this->scores($ids, [90, 80, 80]), $act, 3));      // 2 กับ 3 ซ้ำ
        $this->assertNotNull($this->save($this->scores($ids, [90, 90, 70]), $act, 3));      // 1 กับ 2 ซ้ำ
        $this->assertNull($this->save($this->scores($ids, [90, 80, 70, 60, 60]), $act, 3)); // 4 กับ 5 ซ้ำได้

        // ตัวแทน 3 ทีม -> ห้ามซ้ำลำดับ 1-4
        $this->assertNotNull($this->save($this->scores($ids, [90, 80, 70, 60, 60]), $act, 4));
        $this->assertNull($this->save($this->scores($ids, [90, 80, 70, 65, 60, 60]), $act, 4));

        // ตัวแทน 1 ทีม -> ห้ามซ้ำลำดับ 1-2
        $this->assertNotNull($this->save($this->scores($ids, [90, 90]), $act, 2));
        $this->assertNull($this->save($this->scores($ids, [90, 80, 70, 70]), $act, 2));
    }

    public function test_representative_count_comes_from_config_per_event(): void
    {
        DB::table('configs')->where('event_id', 1)->where('schoolgroup', 0)->update(['representative_count' => null]);
        $this->assertSame(3, TransferReadinessService::representativeCount(1));

        DB::table('configs')->where('event_id', 1)->where('schoolgroup', 0)->update(['representative_count' => 2]);
        $this->assertSame(2, TransferReadinessService::representativeCount(1));
        $this->assertSame([1, 2], TransferReadinessService::transferRanks(1));
        $this->assertSame('1–2', TransferReadinessService::transferRanksLabel(1));
    }
}
