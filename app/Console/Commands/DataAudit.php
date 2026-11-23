<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * ตรวจสุขภาพข้อมูลผู้ใช้/โรงเรียน/เครือข่าย/ศูนย์ (อ่านอย่างเดียว ไม่แก้ข้อมูล)
 * ใช้: php artisan data:audit
 */
class DataAudit extends Command
{
    protected $signature = 'data:audit {--limit=10 : จำนวนตัวอย่างต่อหัวข้อ} {--event= : เลขงาน เช่น 74}';

    protected $description = 'ตรวจความสอดคล้องของ users / schools / schoolgroups / branches (read-only)';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $problems = 0;

        $checks = [
            'บัญชีโรงเรียน (position 0) ไม่มี school_id' =>
                DB::table('users')->where('position', 0)->whereNull('school_id')->select('id', 'email', 'branch', 'schoolgroup')->get(),
            'บัญชีโรงเรียนชี้ school_id ที่ไม่มีอยู่' =>
                DB::table('users as u')->leftJoin('schools as s', 's.id', '=', 'u.school_id')
                    ->where('u.position', 0)->whereNotNull('u.school_id')->whereNull('s.id')->select('u.id', 'u.email', 'u.school_id')->get(),
            'บัญชีโรงเรียน: schoolgroup ไม่ตรงกับ schools.school_group' =>
                DB::table('users as u')->join('schools as s', 's.id', '=', 'u.school_id')
                    ->where('u.position', 0)->whereRaw('NOT (u.schoolgroup <=> s.school_group)')
                    ->select('u.id', 'u.email', 'u.schoolgroup as user_group', 's.school_group as school_group')->get(),
            'โรงเรียนมีหลายบัญชี' =>
                DB::table('users')->where('position', 0)->whereNotNull('school_id')->groupBy('school_id')->havingRaw('count(*) > 1')
                    ->select('school_id', DB::raw('count(*) as n'))->get(),
            'โรงเรียนที่ไม่มีบัญชี' =>
                DB::table('schools as s')->leftJoin('users as u', function ($j) {
                    $j->on('u.school_id', '=', 's.id')->where('u.position', 0);
                })->whereNull('u.id')->select('s.id', 's.school_name', 's.school_code')->get(),
            'โรงเรียนไม่มีรหัส 8 หลัก' =>
                DB::table('schools')->where(function ($q) {
                    $q->whereNull('school_code')->orWhereRaw('school_code NOT REGEXP "^[0-9]{8}$"');
                })->select('id', 'school_name', 'school_code')->get(),
            'รหัสโรงเรียนซ้ำ' =>
                DB::table('schools')->whereNotNull('school_code')->groupBy('school_code')->havingRaw('count(*) > 1')
                    ->select('school_code', DB::raw('count(*) as n'))->get(),
            'โรงเรียนไม่มีเครือข่าย หรือเครือข่ายไม่มีอยู่' =>
                DB::table('schools as s')->leftJoin('schoolgroups as g', 'g.id', '=', 's.school_group')
                    ->whereNull('g.id')->select('s.id', 's.school_name', 's.school_group')->get(),
            'บัญชีเครือข่าย (position 5) ชี้เครือข่ายที่ไม่มี' =>
                DB::table('users as u')->leftJoin('schoolgroups as g', 'g.id', '=', 'u.schoolgroup')
                    ->where('u.position', 5)->whereNull('g.id')->select('u.id', 'u.email', 'u.schoolgroup')->get(),
            'เครือข่ายมีหลายบัญชี' =>
                DB::table('users')->where('position', 5)->groupBy('schoolgroup')->havingRaw('count(*) > 1')
                    ->select('schoolgroup', DB::raw('count(*) as n'))->get(),
            'เครือข่ายที่ไม่มีบัญชี' =>
                DB::table('schoolgroups as g')->leftJoin('users as u', function ($j) {
                    $j->on('u.schoolgroup', '=', 'g.id')->where('u.position', 5);
                })->whereNull('u.id')->select('g.id', 'g.schoolgroup_name')->get(),
            'บัญชีศูนย์ (position 1) branch เป็น NULL/0' =>
                DB::table('users')->where('position', 1)->where(function ($q) {
                    $q->whereNull('branch')->orWhere('branch', 0);
                })->select('id', 'email', 'branch')->get(),
            'บัญชีศูนย์ชี้ศูนย์ที่ไม่ใช่ของงานปัจจุบัน' => $this->centerNotCurrent(),
            'ตำแหน่งผู้ใช้ที่ไม่รู้จัก (ไม่ใช่ 0/1/5/10)' =>
                DB::table('users')->whereNotIn('position', [0, 1, 5, 10])->orWhereNull('position')->select('id', 'email', 'position')->get(),
            'username (email) ซ้ำ' =>
                DB::table('users')->groupBy('email')->havingRaw('count(*) > 1')->select('email', DB::raw('count(*) as n'))->get(),
        ];

        foreach ($checks as $title => $rows) {
            $n = count($rows);
            if ($n === 0) {
                $this->line("<info>[ผ่าน]</info> {$title}");
                continue;
            }
            $problems += $n;
            $this->line("<error>[พบ {$n}]</error> {$title}");
            foreach ($rows->take($limit) as $r) {
                $this->line('      ' . json_encode($r, JSON_UNESCAPED_UNICODE));
            }
            if ($n > $limit) {
                $this->line('      ... อีก ' . ($n - $limit) . ' รายการ');
            }
        }

        $this->newLine();
        $this->line($problems === 0 ? '<info>ข้อมูลสอดคล้องทั้งหมด</info>' : "<comment>รวมรายการที่ต้องตรวจ: {$problems}</comment>");

        return $problems === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function centerNotCurrent()
    {
        $eventId = ($this->option('event') ? DB::table('events')->where('event_number', $this->option('event'))->value('id') : null) ?: (config('app.event_id') ?: DB::table('branches')->max('event_id'));
        if (! $eventId) {
            return collect();
        }

        return DB::table('users as u')->leftJoin('branches as b', function ($j) use ($eventId) {
            $j->on('b.id', '=', 'u.branch')->where('b.event_id', $eventId);
        })->where('u.position', 1)->whereNotNull('u.branch')->where('u.branch', '>', 0)->whereNull('b.id')
            ->select('u.id', 'u.email', 'u.branch')->get();
    }
}
