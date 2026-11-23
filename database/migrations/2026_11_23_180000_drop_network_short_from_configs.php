<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('configs', 'network_short')) {
            Schema::table('configs', fn (Blueprint $table) => $table->dropColumn('network_short'));
        }
    }

    public function down(): void
    {
    }
};
