<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $tables = [
        'dayofsillapas',
        'notices',
        'pictures',
        'subjectgroups',
        'activities',
        'activities_areas',
        'branches'
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'year')) {
                        $table->string('year', 4)->default('2569')->after('id');
                        $table->index('year');
                    }
                });

                // Backfill current records to year 2569
                DB::table($tableName)->whereNull('year')->orWhere('year', '')->update(['year' => '2569']);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (Schema::hasColumn($tableName, 'year')) {
                        $table->dropIndex(['year']);
                        $table->dropColumn('year');
                    }
                });
            }
        }
    }
};
