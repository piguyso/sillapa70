<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            // จำนวนตัวแทนเครือข่ายที่เข้าระดับเขต = อันดับที่ 1..N (ว่าง = ใช้ค่าเริ่มต้น 3) ตั้งแยกตามครั้งการแข่งขัน
            $table->unsignedTinyInteger('representative_count')->nullable()->after('network_label');
        });

        // ครั้งที่ 74 ประกาศให้ส่งตัวแทนอันดับ 1-2 (ค่าเดิมที่เคยฮาร์ดโค้ดไว้)
        DB::table('configs')
            ->whereIn('event_id', DB::table('events')->where('event_number', 74)->pluck('id'))
            ->update(['representative_count' => 2]);
    }

    public function down(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn('representative_count');
        });
    }
};
