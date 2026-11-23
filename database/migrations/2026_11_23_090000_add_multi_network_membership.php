<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schoolgroups', function (Blueprint $table) {
            // district = เครือข่ายระดับอำเภอ, cluster = กลุ่มโรงเรียน
            $table->string('group_type', 20)->default('cluster')->after('schoolgroup_detail');
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->boolean('is_expansion')->default(false)->after('school_group');
        });

        Schema::create('school_schoolgroup', function (Blueprint $table) {
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('schoolgroup_id');
            $table->timestamps();
            $table->primary(['school_id', 'schoolgroup_id']);
            $table->index('schoolgroup_id');
        });

        // โอนสังกัดเดิม (school_group) เข้าตารางเชื่อม โดย school_group ยังเป็นเครือข่ายหลัก
        $now = now();
        DB::table('schools')->whereNotNull('school_group')->orderBy('id')->each(function ($s) use ($now) {
            DB::table('school_schoolgroup')->insertOrIgnore([
                'school_id' => $s->id, 'schoolgroup_id' => $s->school_group,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_schoolgroup');
        Schema::table('schools', fn (Blueprint $t) => $t->dropColumn('is_expansion'));
        Schema::table('schoolgroups', fn (Blueprint $t) => $t->dropColumn('group_type'));
    }
};
