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
        Schema::table('configs', function (Blueprint $table) {
            $table->text('link_criteria')->nullable()->after('app_logo');
            $table->text('link_ai')->nullable()->after('link_criteria');
            $table->text('link_survey')->nullable()->after('link_ai');
            $table->string('survey_qr')->nullable()->after('link_survey');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn(['link_criteria', 'link_ai', 'link_survey', 'survey_qr']);
        });
    }
};
