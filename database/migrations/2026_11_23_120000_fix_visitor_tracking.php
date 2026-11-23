<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ข้อมูลที่ไม่ใช่ผู้เข้าชมจริง (เครื่องเอง / ไม่มี visitor_id) และรายการซ้ำของ visitor_id เดียวกัน
        DB::table('visitor_logs')->whereNull('visitor_id')->orWhereIn('ip_address', ['127.0.0.1', '::1'])->delete();
        $keep = DB::table('visitor_logs')->select(DB::raw('max(id) as id'))->groupBy('visitor_id')->pluck('id');
        DB::table('visitor_logs')->whereNotIn('id', $keep)->delete();

        Schema::table('visitor_logs', function (Blueprint $table) {
            $table->unique('visitor_id');
            $table->index('last_activity');
        });

        // นับผู้เข้าชมสะสมแบบไม่ซ้ำ (visitor_logs ถูกลบทุก 15 นาที จึงใช้นับยอดรวมไม่ได้)
        Schema::create('visitor_totals', function (Blueprint $table) {
            $table->string('visitor_id', 64)->primary();
            $table->timestamp('first_seen_at')->useCurrent();
        });
        DB::table('visitor_logs')->orderBy('id')->each(fn ($v) => DB::table('visitor_totals')->insertOrIgnore([
            'visitor_id' => $v->visitor_id, 'first_seen_at' => $v->created_at ?? now(),
        ]));
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_totals');
        Schema::table('visitor_logs', function (Blueprint $table) {
            $table->dropUnique(['visitor_id']);
            $table->dropIndex(['last_activity']);
        });
    }
};
