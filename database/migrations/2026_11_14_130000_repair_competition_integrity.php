<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('competition_rank_repair_backups')) {
            Schema::create('competition_rank_repair_backups', function (Blueprint $table): void {
                $table->id();
                $table->string('table_name', 64);
                $table->unsignedBigInteger('row_id');
                $table->integer('old_rank');
                $table->integer('new_rank');
                $table->timestamps();
                $table->unique(['table_name', 'row_id']);
            });
        }

        if (! Schema::hasTable('orphan_area_people_backups')) {
            Schema::create('orphan_area_people_backups', function (Blueprint $table): void {
                $table->unsignedBigInteger('row_id')->primary();
                $table->json('payload');
                $table->timestamps();
            });
        }

        $this->repairRanks('addschools', ['event_id', 'school_activity']);
        $this->repairRanks('addschools_areas', ['event_id', 'school_activity', 'school_schoolgroup']);

        $activeEventId = DB::table('events')->where('is_active', 1)->value('id');
        $orphans = DB::table('addstudents_areas as people')
            ->leftJoin('addschools_areas as teams', function ($join): void {
                $join->on('teams.id', '=', 'people.school')
                    ->on('teams.event_id', '=', 'people.event_id');
            })
            ->where('people.event_id', $activeEventId)
            ->whereNull('teams.id')
            ->select('people.*')
            ->get();

        foreach ($orphans as $orphan) {
            DB::table('orphan_area_people_backups')->updateOrInsert(
                ['row_id' => $orphan->id],
                ['payload' => json_encode($orphan, JSON_UNESCAPED_UNICODE), 'updated_at' => now(), 'created_at' => now()],
            );
        }

        if ($orphans->isNotEmpty()) {
            DB::table('addstudents_areas')->whereIn('id', $orphans->pluck('id'))->delete();
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('competition_rank_repair_backups')) {
            foreach (DB::table('competition_rank_repair_backups')->orderBy('id')->get() as $backup) {
                if (Schema::hasTable($backup->table_name)) {
                    DB::table($backup->table_name)->where('id', $backup->row_id)->update(['activity_rank' => $backup->old_rank]);
                }
            }
        }

        if (Schema::hasTable('orphan_area_people_backups')) {
            foreach (DB::table('orphan_area_people_backups')->get() as $backup) {
                $row = (array) json_decode($backup->payload, true);
                if ($row !== [] && ! DB::table('addstudents_areas')->where('id', $backup->row_id)->exists()) {
                    DB::table('addstudents_areas')->insert($row);
                }
            }
        }

        Schema::dropIfExists('competition_rank_repair_backups');
        Schema::dropIfExists('orphan_area_people_backups');
    }

    private function repairRanks(string $table, array $groupColumns): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $rows = DB::table($table)
            ->where('activity_status', 1)
            ->where('student_score', '>', 0)
            ->whereNotIn('activity_rank', [98, 99])
            ->orderBy('student_score', 'desc')
            ->get();

        foreach ($rows->groupBy(fn ($row) => implode(':', array_map(fn ($column) => $row->{$column}, $groupColumns))) as $group) {
            $position = 0;
            $rank = 0;
            $previousScore = null;
            foreach ($group->sortByDesc('student_score')->values() as $row) {
                $position++;
                $score = (float) $row->student_score;
                if ($previousScore === null || $score !== $previousScore) {
                    $rank = $position;
                }
                if ((int) $row->activity_rank !== $rank) {
                    DB::table('competition_rank_repair_backups')->updateOrInsert(
                        ['table_name' => $table, 'row_id' => $row->id],
                        ['old_rank' => $row->activity_rank, 'new_rank' => $rank, 'updated_at' => now(), 'created_at' => now()],
                    );
                    DB::table($table)->where('id', $row->id)->update(['activity_rank' => $rank]);
                }
                $previousScore = $score;
            }
        }
    }
};
