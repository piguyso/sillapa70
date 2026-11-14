<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $yearTables = [
        'subjectgroups',
        'activities',
        'activities_areas',
        'branches',
        'dayofsillapas',
        'notices',
        'pictures',
        'addschools',
        'addstudents',
        'jows',
        'schoolranks',
        'addschools_areas',
        'addstudents_areas',
        'jows_areas',
    ];

    private array $configTables = [
        'configs',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->unsignedSmallInteger('event_number')->unique();
                $table->string('academic_year', 4)->index();
                $table->string('name');
                $table->string('status', 20)->default('draft');
                $table->boolean('is_active')->default(false)->index();
                $table->timestamps();
            });
        }

        $hasActiveEvent = DB::table('events')->where('is_active', true)->exists();

        if (DB::table('events')->where('event_number', 73)->doesntExist()) {
            DB::table('events')->insert([
                'event_number' => 73,
                'academic_year' => '2568',
                'name' => 'งานศิลปหัตถกรรมนักเรียน ครั้งที่ 73',
                'status' => 'archived',
                'is_active' => false,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }

        if (DB::table('events')->where('event_number', 74)->doesntExist()) {
            DB::table('events')->insert([
                'event_number' => 74,
                'academic_year' => '2569',
                'name' => 'งานศิลปหัตถกรรมนักเรียน ครั้งที่ 74',
                'status' => $hasActiveEvent ? 'archived' : 'active',
                'is_active' => ! $hasActiveEvent,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
            $hasActiveEvent = true;
        }

        // Preserve and register any other existing years found in configs or years tables
        $existingYears = collect();
        if (Schema::hasTable('years') && Schema::hasColumn('years', 'years')) {
            $existingYears = $existingYears->merge(DB::table('years')->pluck('years'));
        }
        if (Schema::hasTable('configs') && Schema::hasColumn('configs', 'app_year')) {
            $existingYears = $existingYears->merge(DB::table('configs')->whereNotNull('app_year')->pluck('app_year'));
        }

        foreach ($existingYears->map(fn ($y) => trim((string) $y))->filter()->unique() as $yearStr) {
            if (! preg_match('/^\d{4}$/', $yearStr)) {
                continue;
            }
            if (DB::table('events')->where('academic_year', $yearStr)->exists()) {
                continue;
            }
            $inferredNumber = max(1, 74 + ((int) $yearStr - 2569));
            while (DB::table('events')->where('event_number', $inferredNumber)->exists()) {
                $inferredNumber++;
            }
            $configName = Schema::hasTable('configs')
                ? DB::table('configs')->where('app_year', $yearStr)->where('schoolgroup', 0)->value('app_name')
                : null;
            DB::table('events')->insert([
                'event_number' => $inferredNumber,
                'academic_year' => $yearStr,
                'name' => $configName ?: ('งานศิลปหัตถกรรมนักเรียน ครั้งที่ ' . $inferredNumber),
                'status' => 'archived',
                'is_active' => false,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }

        foreach (array_merge($this->yearTables, $this->configTables) as $tableName) {
            if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'event_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('event_id')->nullable()->index()->after('id');
                });
            }
        }

        $eventsByYear = DB::table('events')->pluck('id', 'academic_year');

        foreach ($this->yearTables as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'year')) {
                continue;
            }

            foreach ($eventsByYear as $academicYear => $eventId) {
                DB::table($tableName)
                    ->whereNull('event_id')
                    ->where('year', (string) $academicYear)
                    ->update(['event_id' => $eventId]);
            }
        }

        foreach ($this->configTables as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'app_year')) {
                continue;
            }

            foreach ($eventsByYear as $academicYear => $eventId) {
                DB::table($tableName)
                    ->whereNull('event_id')
                    ->where('app_year', (string) $academicYear)
                    ->update(['event_id' => $eventId]);
            }
        }
    }

    public function down(): void
    {
        // Do not drop events table or event_id columns to preserve existing data
    }
};
