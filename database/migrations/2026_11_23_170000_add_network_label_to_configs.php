<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            // ชื่อเรียก "เครือข่ายสถานศึกษา" ที่ใช้แสดงทั้งเว็บ (ว่าง = ใช้ชื่อเดิม)
            $table->string('network_label', 100)->nullable()->after('app_area');
        });
    }

    public function down(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn('network_label');
        });
    }
};
