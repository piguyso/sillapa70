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
        if (! Schema::hasTable('visitor_logs')) {
            return;
        }

        Schema::table('visitor_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('visitor_logs', 'visitor_id')) {
                $table->string('visitor_id', 64)->nullable()->index()->after('session_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not drop column
    }
};
