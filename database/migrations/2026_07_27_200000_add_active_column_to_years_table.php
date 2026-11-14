<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveColumnToYearsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('years')) {
            return;
        }

        Schema::table('years', function (Blueprint $table) {
            if (! Schema::hasColumn('years', 'active')) {
                $table->tinyInteger('active')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not drop column containing existing active year state
    }
}
