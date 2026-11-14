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
            if (! Schema::hasColumn('configs', 'link_criteria')) {
                $table->text('link_criteria')->nullable()->after('app_logo');
            }
            if (! Schema::hasColumn('configs', 'link_ai')) {
                $table->text('link_ai')->nullable()->after('link_criteria');
            }
            if (! Schema::hasColumn('configs', 'link_survey')) {
                $table->text('link_survey')->nullable()->after('link_ai');
            }
            if (! Schema::hasColumn('configs', 'survey_qr')) {
                $table->string('survey_qr')->nullable()->after('link_survey');
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
