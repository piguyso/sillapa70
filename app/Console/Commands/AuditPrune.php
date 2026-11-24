<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AuditPrune extends Command
{
    protected $signature = 'audit:prune {--days=365 : เก็บ log ย้อนหลังกี่วัน}';

    protected $description = 'ลบ audit_logs ที่เก่ากว่าจำนวนวันที่กำหนด';

    public function handle(): int
    {
        $days = max(30, (int) $this->option('days'));
        $n = DB::table('audit_logs')->where('created_at', '<', now()->subDays($days))->delete();
        $this->info("ลบ {$n} รายการ (เก่ากว่า {$days} วัน)");

        return self::SUCCESS;
    }
}
