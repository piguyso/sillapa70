<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legacy_orphan_area_people_backups', function (Blueprint $table): void {
            $table->unsignedBigInteger('row_id')->primary();
            $table->json('payload');
            $table->timestamps();
        });

        $orphans = DB::table('addstudents_areas as people')
            ->leftJoin('addschools_areas as teams', function ($join): void {
                $join->on('teams.id', '=', 'people.school')
                    ->on('teams.event_id', '=', 'people.event_id');
            })
            ->whereNull('teams.id')
            ->select('people.*')
            ->get();

        foreach ($orphans as $orphan) {
            DB::table('legacy_orphan_area_people_backups')->insert([
                'row_id' => $orphan->id,
                'payload' => json_encode($orphan, JSON_UNESCAPED_UNICODE),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($orphans->isNotEmpty()) {
            DB::table('addstudents_areas')->whereIn('id', $orphans->pluck('id'))->delete();
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('legacy_orphan_area_people_backups')) {
            return;
        }

        foreach (DB::table('legacy_orphan_area_people_backups')->get() as $backup) {
            $row = (array) json_decode($backup->payload, true);
            if ($row !== [] && ! DB::table('addstudents_areas')->where('id', $backup->row_id)->exists()) {
                DB::table('addstudents_areas')->insert($row);
            }
        }

        Schema::dropIfExists('legacy_orphan_area_people_backups');
    }
};
