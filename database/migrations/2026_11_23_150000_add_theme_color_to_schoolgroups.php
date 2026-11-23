<?php

use App\Schoolgroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schoolgroups', function (Blueprint $table) {
            $table->string('theme_color', 7)->nullable()->after('group_type');
        });

        // ค่าเริ่มต้น: แจกสีจากชุดสีของระบบให้เครือข่ายเดิมตามลำดับ (ผู้ดูแลแก้ทีหลังได้)
        $i = 0;
        foreach (DB::table('schoolgroups')->orderBy('id')->pluck('id') as $id) {
            DB::table('schoolgroups')->where('id', $id)->update(['theme_color' => Schoolgroup::PALETTE[$i % count(Schoolgroup::PALETTE)]]);
            $i++;
        }
    }

    public function down(): void
    {
        Schema::table('schoolgroups', fn (Blueprint $t) => $t->dropColumn('theme_color'));
    }
};
