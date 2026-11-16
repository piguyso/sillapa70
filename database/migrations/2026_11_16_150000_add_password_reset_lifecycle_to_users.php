<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('must_change_password')->default(false)->after('password');
            $table->unsignedInteger('password_version')->default(1)->after('must_change_password');
            $table->timestamp('password_changed_at')->nullable()->after('password_version');
            $table->unsignedBigInteger('password_reset_by')->nullable()->after('password_changed_at');
            $table->timestamp('password_reset_at')->nullable()->after('password_reset_by');
        });

        Schema::create('user_password_reset_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_user_id');
            $table->string('target_type', 32);
            $table->unsignedBigInteger('target_id')->nullable();
            $table->unsignedInteger('affected_count');
            $table->json('affected_user_ids');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_password_reset_logs');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'must_change_password', 'password_version', 'password_changed_at',
                'password_reset_by', 'password_reset_at',
            ]);
        });
    }
};

