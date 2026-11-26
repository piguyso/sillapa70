<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notice_views')) {
            return;
        }
        Schema::create('notice_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notice_id')->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('visitor_hash', 64);
            $table->string('source', 10)->default('district');
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('viewed_at')->useCurrent()->index();
            $table->index(['notice_id', 'visitor_hash', 'viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notice_views');
    }
};
