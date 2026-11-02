<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * เพิ่มคอลัมน์ visitor_id สำหรับติดตามผู้เข้าชมแบบ unique โดยใช้ cookie
     */
    public function up(): void
    {
        Schema::table('visitor_logs', function (Blueprint $table) {
            // เพิ่ม visitor_id — UUID ที่เก็บใน cookie ถาวร ไม่เปลี่ยนตาม session
            if (!Schema::hasColumn('visitor_logs', 'visitor_id')) {
                $table->string('visitor_id', 64)->nullable()->index()->after('session_id');
            }
        });

        // ลบข้อมูลเก่า > 30 นาที ออกจากตาราง ก่อน migrate
        try {
            \DB::table('visitor_logs')
                ->where('last_activity', '<', now()->subMinutes(30))
                ->delete();
        } catch (\Exception $e) {
            // ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitor_logs', function (Blueprint $table) {
            if (Schema::hasColumn('visitor_logs', 'visitor_id')) {
                $table->dropColumn('visitor_id');
            }
        });
    }
};

