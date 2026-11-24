<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('audit_logs')) {
            return;
        }
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent()->index();
            $table->unsignedBigInteger('event_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('username', 255)->nullable()->index();      // ชื่อบัญชี ณ ตอนนั้น (กันบัญชีถูกลบ/เปลี่ยนชื่อ)
            $table->unsignedTinyInteger('user_position')->nullable();
            $table->string('action', 30)->index();                      // created|updated|deleted|request|login|logout|login_failed
            $table->string('subject_table', 100)->nullable()->index();
            $table->string('subject_id', 64)->nullable();
            $table->string('description', 500)->nullable();
            $table->longText('changes')->nullable();                    // JSON: ก่อน/หลัง หรือพารามิเตอร์ของคำขอ
            $table->string('method', 10)->nullable();
            $table->string('url', 500)->nullable();
            $table->unsignedSmallInteger('status')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
