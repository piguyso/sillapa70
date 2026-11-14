<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('configs')) {
            return;
        }

        Schema::table('configs', function (Blueprint $table) {
            if (! Schema::hasColumn('configs', 'theme_primary')) {
                $table->string('theme_primary', 7)->default('#059669')->after('app_slogan');
            }
            if (! Schema::hasColumn('configs', 'theme_secondary')) {
                $table->string('theme_secondary', 7)->default('#0f172a')->after('theme_primary');
            }
        });
    }

    public function down(): void
    {
        // Do not drop columns containing existing theme colors
    }
};
