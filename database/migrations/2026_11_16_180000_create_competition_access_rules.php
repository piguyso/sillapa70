<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_access_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_id')->index();
            $table->string('competition_level', 20);
            $table->string('subject_type', 20);
            $table->string('scope_type', 20);
            $table->unsignedBigInteger('scope_id')->default(0);
            $table->boolean('is_closed')->default(false);
            $table->string('message', 500)->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->timestamps();

            $table->unique(
                ['event_id', 'competition_level', 'subject_type', 'scope_type', 'scope_id'],
                'competition_access_rule_unique'
            );
        });

        Schema::create('competition_access_rule_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_id')->index();
            $table->unsignedBigInteger('rule_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_access_rule_logs');
        Schema::dropIfExists('competition_access_rules');
    }
};
