<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // ชื่อไฟล์โลโก้ (WebP) ในโฟลเดอร์ upload ของ storage
            $table->string('school_logo', 120)->nullable()->after('school_code');
        });
    }

    public function down(): void
    {
        Schema::table('schools', fn (Blueprint $table) => $table->dropColumn('school_logo'));
    }
};
