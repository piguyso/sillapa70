<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $marker = '[ย้ายจากระบบเดิม] ';

    public function up(): void
    {
        if (! Schema::hasTable('competition_access_rules') || ! Schema::hasTable('systemactives')) {
            return;
        }
        $eventId = (int) DB::table('events')->where('is_active', 1)->value('id');
        if ($eventId < 1) return;

        foreach (DB::table('systemactives')->get() as $legacy) {
            $mapping = match (true) {
                (int) $legacy->id === 15 => ['network', 'master', 'all', 0],
                $legacy->systemname === 'center' => ['district', 'center', 'all', 0],
                $legacy->systemname === 'area' => ['district', 'network', 'all', 0],
                $legacy->systemname === 'school' && (int) $legacy->schoolgroup > 0
                    => ['network', 'school', 'network', (int) $legacy->schoolgroup],
                default => null,
            };
            if (! $mapping) continue;

            [$level, $subject, $scope, $scopeId] = $mapping;
            DB::table('competition_access_rules')->updateOrInsert([
                'event_id' => $eventId, 'competition_level' => $level,
                'subject_type' => $subject, 'scope_type' => $scope, 'scope_id' => $scopeId,
            ], [
                'is_closed' => (bool) $legacy->status,
                'message' => $this->marker.($legacy->detail ?: 'ระบบปิดรับการแก้ไขข้อมูล'),
                'changed_by' => null, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('competition_access_rules')->where('message', 'like', $this->marker.'%')->delete();
    }
};
