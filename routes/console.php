<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('sillapa:migrate-safe', function () {
    $this->info('1. กำลังตรวจสอบและอัปเดตโครงสร้างฐานข้อมูล (Non-destructive Migration)...');
    $this->call('migrate', ['--force' => true]);

    $yearTables = [
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

    $this->info('2. กำลังตรวจสอบและเชื่อมโยงข้อมูลเดิมเข้ากับระบบครั้งการแข่งขัน (Events) โดยไม่ลบข้อมูลเก่า...');

    // Discover any years in years, configs, or yearTables not yet in events
    $discoveredYears = collect();
    if (Schema::hasTable('years') && Schema::hasColumn('years', 'years')) {
        $discoveredYears = $discoveredYears->merge(DB::table('years')->pluck('years'));
    }
    if (Schema::hasTable('configs') && Schema::hasColumn('configs', 'app_year')) {
        $discoveredYears = $discoveredYears->merge(DB::table('configs')->whereNotNull('app_year')->pluck('app_year'));
    }
    foreach ($yearTables as $tableName) {
        if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'year')) {
            $discoveredYears = $discoveredYears->merge(DB::table($tableName)->select('year')->distinct()->pluck('year'));
        }
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
        $hasActive = DB::table('events')->where('is_active', true)->exists();
        DB::table('events')->insert([
            'event_number' => $inferredNumber,
            'academic_year' => $yearStr,
            'name' => $configName ?: ('งานศิลปหัตถกรรมนักเรียน ครั้งที่ ' . $inferredNumber),
            'status' => $hasActive ? 'archived' : 'active',
            'is_active' => ! $hasActive,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->line("   + สร้างรายการ Event ครั้งที่ {$inferredNumber} (ปี {$yearStr}) สำหรับข้อมูลเดิมอัตโนมัติ");
    }

    $events = DB::table('events')->orderBy('event_number')->get();
    foreach ($events as $event) {
        $yearStr = (string) $event->academic_year;

        // Ensure years table has this year
        if (Schema::hasTable('years') && DB::table('years')->where('years', $yearStr)->doesntExist()) {
            DB::table('years')->insert([
                'years' => $yearStr,
                'active' => $event->is_active ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Ensure main configs record (schoolgroup = 0) exists for this event
        if (Schema::hasTable('configs')) {
            DB::table('configs')
                ->whereNull('event_id')
                ->where('app_year', $yearStr)
                ->update(['event_id' => $event->id]);

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

        // Backfill event_id on year-scoped tables where event_id is NULL
        foreach ($yearTables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'event_id') && Schema::hasColumn($tableName, 'year')) {
                DB::table($tableName)
                    ->whereNull('event_id')
                    ->where('year', $yearStr)
                    ->update(['event_id' => $event->id]);
            }
        }

        // Ensure storage directories exist for each event year
        Storage::disk('public')->makeDirectory($yearStr . '/upload');
        Storage::disk('public')->makeDirectory($yearStr . '/picture');
    }

    $this->info('3. กำลังล้าง Cache ของระบบ...');
    $this->call('optimize:clear');

    $rows = [];
    foreach (DB::table('events')->orderBy('event_number')->get() as $ev) {
        $rows[] = [
            $ev->event_number,
            $ev->academic_year,
            $ev->name,
            $ev->is_active ? 'ACTIVE' : 'INACTIVE',
            Schema::hasTable('activities') ? DB::table('activities')->where('event_id', $ev->id)->count() : 0,
            Schema::hasTable('addschools') ? DB::table('addschools')->where('event_id', $ev->id)->count() : 0,
            Schema::hasTable('addstudents') ? DB::table('addstudents')->where('event_id', $ev->id)->count() : 0,
        ];
    }
    $this->table(
        ['ครั้งที่', 'ปี พ.ศ.', 'ชื่องาน', 'สถานะ', 'กิจกรรม', 'ทีมโรงเรียน', 'นักเรียน/ครู'],
        $rows
    );

    $this->info('ดำเนินการเตรียมฐานข้อมูลและเชื่อมโยงข้อมูลเดิมเสร็จสมบูรณ์ (ไม่มีการลบข้อมูลเก่า)');
})->purpose('Safely migrate schema and link existing database records without deleting any data');
