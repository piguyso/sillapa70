<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // รหัสโรงเรียน 8 หลัก (SMIS) โรงเรียนเอกชนที่ไม่พบใน SMIS ใช้ 86019xxx
            $table->string('school_code', 8)->nullable()->unique()->after('school_name');
        });
    }

    public function down(): void
    {
        Schema::table('schools', fn (Blueprint $table) => $table->dropColumn('school_code'));
    }
};
