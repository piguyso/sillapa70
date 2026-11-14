<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $yearTables = [
        'subjectgroups' => '2569',
        'activities' => '2569',
        'activities_areas' => '2569',
        'branches' => '2569',
        'dayofsillapas' => '2569',
        'notices' => '2569',
        'pictures' => '2569',
        'addschools' => '2570',
        'addstudents' => '2570',
        'jows' => '2570',
        'schoolranks' => '2570',
        'addschools_areas' => '2570',
        'addstudents_areas' => '2570',
        'jows_areas' => '2570',
    ];

    /**
     * Safely ensure all required columns/tables exist and link existing records
     * to events without deleting or overwriting any existing data.
     */
    public function up(): void
    {
        // 1. Ensure events table exists
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

        // 2. Ensure years.active column exists
        if (Schema::hasTable('years') && ! Schema::hasColumn('years', 'active')) {
            Schema::table('years', function (Blueprint $table) {
                $table->tinyInteger('active')->default(0);
            });
        }

        // 3. Ensure configs columns exist
        if (Schema::hasTable('configs')) {
            Schema::table('configs', function (Blueprint $table) {
                if (! Schema::hasColumn('configs', 'event_id')) {
                    $table->unsignedBigInteger('event_id')->nullable()->index()->after('id');
                }
                if (! Schema::hasColumn('configs', 'app_slogan')) {
                    $table->text('app_slogan')->nullable()->after('app_name');
                }
                if (! Schema::hasColumn('configs', 'theme_primary')) {
                    $table->string('theme_primary', 7)->default('#059669')->after('app_slogan');
                }
                if (! Schema::hasColumn('configs', 'theme_secondary')) {
                    $table->string('theme_secondary', 7)->default('#0f172a')->after('theme_primary');
                }
                if (! Schema::hasColumn('configs', 'link_criteria')) {
                    $table->text('link_criteria')->nullable()->after('app_logo');
                }
                if (! Schema::hasColumn('configs', 'link_ai')) {
                    $table->text('link_ai')->nullable()->after('link_criteria');
                }
                if (! Schema::hasColumn('configs', 'link_survey')) {
                    $table->text('link_survey')->nullable()->after('link_ai');
                }
                if (! Schema::hasColumn('configs', 'survey_qr')) {
                    $table->string('survey_qr')->nullable()->after('link_survey');
                }
            });
        }

        // 4. Ensure year & event_id columns exist on all year-scoped tables
        foreach ($this->yearTables as $tableName => $defaultYear) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName, $defaultYear) {
                if (! Schema::hasColumn($tableName, 'year')) {
                    $table->string('year', 4)->default($defaultYear)->after('id')->index();
                }
                if (! Schema::hasColumn($tableName, 'event_id')) {
                    $table->unsignedBigInteger('event_id')->nullable()->index()->after('id');
                }
            });
        }

        // 5. Discover all existing years across years, configs, and yearTables without overwriting existing events
        $discoveredYears = collect();
        if (Schema::hasTable('years') && Schema::hasColumn('years', 'years')) {
            $discoveredYears = $discoveredYears->merge(DB::table('years')->pluck('years'));
        }
        if (Schema::hasTable('configs') && Schema::hasColumn('configs', 'app_year')) {
            $discoveredYears = $discoveredYears->merge(DB::table('configs')->whereNotNull('app_year')->pluck('app_year'));
        }
        foreach (array_keys($this->yearTables) as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'year')) {
                $discoveredYears = $discoveredYears->merge(DB::table($tableName)->select('year')->distinct()->pluck('year'));
            }
        }

        $activeYearFromYearsTable = null;
        if (Schema::hasTable('years') && Schema::hasColumn('years', 'active')) {
            $activeYearFromYearsTable = DB::table('years')->where('active', 1)->value('years');
        }

        foreach ($discoveredYears->map(fn ($y) => trim((string) $y))->filter()->unique()->sort() as $yearStr) {
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

            $hasAnyActiveEvent = DB::table('events')->where('is_active', true)->exists();
            $shouldActivate = ! $hasAnyActiveEvent && ($activeYearFromYearsTable === $yearStr);

            DB::table('events')->insert([
                'event_number' => $inferredNumber,
                'academic_year' => $yearStr,
                'name' => $configName ?: ('งานศิลปหัตถกรรมนักเรียน ครั้งที่ ' . $inferredNumber),
                'status' => $shouldActivate ? 'active' : 'archived',
                'is_active' => $shouldActivate,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Ensure at least one active event exists if events table is non-empty
        if (DB::table('events')->count() > 0 && DB::table('events')->where('is_active', true)->doesntExist()) {
            $latestEventId = DB::table('events')->orderByDesc('event_number')->value('id');
            if ($latestEventId) {
                DB::table('events')->where('id', $latestEventId)->update([
                    'is_active' => true,
                    'status' => 'active',
                ]);
            }
        }

        // 6. Backfill event_id ONLY where event_id is NULL (never overwrite existing links)
        $eventsByYear = DB::table('events')->pluck('id', 'academic_year');

        foreach (array_keys($this->yearTables) as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'event_id') || ! Schema::hasColumn($tableName, 'year')) {
                continue;
            }

            foreach ($eventsByYear as $academicYear => $eventId) {
                DB::table($tableName)
                    ->whereNull('event_id')
                    ->where('year', (string) $academicYear)
                    ->update(['event_id' => $eventId]);
            }
        }

        if (Schema::hasTable('configs') && Schema::hasColumn('configs', 'event_id') && Schema::hasColumn('configs', 'app_year')) {
            foreach ($eventsByYear as $academicYear => $eventId) {
                DB::table('configs')
                    ->whereNull('event_id')
                    ->where('app_year', (string) $academicYear)
                    ->update(['event_id' => $eventId]);
            }
        }

        // 7. Ensure years, main configs row (schoolgroup = 0), and storage folders exist for every event
        foreach (DB::table('events')->orderBy('event_number')->get() as $event) {
            $yearStr = (string) $event->academic_year;

            if (Schema::hasTable('years') && DB::table('years')->where('years', $yearStr)->doesntExist()) {
                DB::table('years')->insert([
                    'years' => $yearStr,
                    'active' => $event->is_active ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (Schema::hasTable('configs')) {
                $hasMainConfig = DB::table('configs')
                    ->where('event_id', $event->id)
                    ->where('schoolgroup', 0)
                    ->exists();

                if (! $hasMainConfig) {
                    DB::table('configs')->insert([
                        'event_id' => $event->id,
                        'app_name' => $event->name,
                        'app_slogan' => 'หัตถกรรมล้ำค่า วิชาการก้าวหน้า พัฒนาเด็กไทย สู่สากล',
                        'theme_primary' => '#059669',
                        'theme_secondary' => '#0f172a',
                        'app_group' => 'ระดับเขตพื้นที่การศึกษา',
                        'app_area' => 'สำนักงานเขตพื้นที่การศึกษาประถมศึกษาชุมพร เขต 1',
                        'app_year' => $yearStr,
                        'schoolgroup' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            try {
                \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($yearStr . '/upload');
                \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($yearStr . '/picture');
            } catch (\Throwable $e) {
                // Ignore filesystem permission issues during CLI migration
            }
        }
    }

    public function down(): void
    {
        // Non-destructive migration: never drop existing data
    }
};
