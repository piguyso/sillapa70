<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('visitor_logs')) {
            Schema::create('visitor_logs', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address', 45);
                $table->string('session_id', 191)->index();
                $table->string('visitor_id', 64)->nullable()->index();
                $table->string('url')->nullable();
                $table->timestamp('last_activity')->useCurrent()->useCurrentOnUpdate();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not drop existing visitor_logs table
    }
};
