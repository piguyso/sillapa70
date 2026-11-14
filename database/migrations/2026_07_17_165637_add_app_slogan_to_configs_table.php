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
        if (! Schema::hasTable('configs')) {
            return;
        }

        Schema::table('configs', function (Blueprint $table) {
            if (! Schema::hasColumn('configs', 'app_slogan')) {
                $table->text('app_slogan')->nullable()->after('app_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not drop columns containing existing configuration data
    }
};
