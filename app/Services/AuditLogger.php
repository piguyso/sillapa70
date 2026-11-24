<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * บันทึกกิจกรรมของผู้ใช้ลงตาราง audit_logs (ใครทำอะไร เมื่อไร จากไหน)
 * ห้ามทำให้ระบบหลักล้มเหลวถ้าบันทึกไม่ได้ จึงดักทุกข้อผิดพลาดไว้
 */
class AuditLogger
{
    /** ตารางที่ไม่บันทึก (เป็นตาราง log/ชั่วคราวเอง) */
    public const SKIP_TABLES = ['audit_logs', 'visitor_logs', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs', 'password_reset_tokens'];

    /** ฟิลด์ที่ห้ามเก็บค่าจริง */
    public const SECRET_FIELDS = ['password', 'remember_token'];

    private static ?bool $tableExists = null;

    public static function enabled(): bool
    {
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return false; // คำสั่ง artisan ไม่บันทึก (ไม่มีผู้ใช้)
        }
        try {
            return self::$tableExists ??= \Illuminate\Support\Facades\Schema::hasTable('audit_logs');
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function record(string $action, array $data = []): void
    {
        if (! self::enabled()) {
            return;
        }
        try {
            $user = Auth::user();
            $request = request();
            DB::table('audit_logs')->insert([
                'created_at' => now(),
                'event_id' => function_exists('active_event_id') ? active_event_id() : null,
                'user_id' => $data['user_id'] ?? $user?->id,
                'username' => mb_substr((string) ($data['username'] ?? $user?->email ?? ''), 0, 255) ?: null,
                'user_position' => $user?->position,
                'action' => $action,
                'subject_table' => $data['subject_table'] ?? null,
                'subject_id' => isset($data['subject_id']) ? mb_substr((string) $data['subject_id'], 0, 64) : null,
                'description' => isset($data['description']) ? mb_substr((string) $data['description'], 0, 500) : null,
                'changes' => isset($data['changes']) ? json_encode($data['changes'], JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR) : null,
                'method' => $request?->method(),
                'url' => $request ? mb_substr($request->fullUrl(), 0, 500) : null,
                'status' => $data['status'] ?? null,
                'ip_address' => $request?->ip(),
                'user_agent' => $request ? mb_substr((string) $request->userAgent(), 0, 255) : null,
            ]);
        } catch (\Throwable $e) {
            // ไม่ให้การบันทึก log ทำให้งานหลักล้ม
        }
    }

    /** บันทึกการเพิ่ม/แก้/ลบของ Eloquent model */
    public static function model(string $action, Model $model): void
    {
        $table = $model->getTable();
        if (in_array($table, self::SKIP_TABLES, true) || ! self::enabled()) {
            return;
        }

        if ($action === 'updated') {
            $new = [];
            $old = [];
            foreach ($model->getChanges() as $key => $value) {
                if (in_array($key, ['updated_at', 'remember_token'], true)) {
                    continue; // ค่าที่เปลี่ยนเองทุกครั้งที่เข้า/ออกระบบ ไม่ใช่การกระทำของผู้ใช้
                }
                $new[$key] = self::clean($key, $value);
                $old[$key] = self::clean($key, $model->getOriginal($key));
            }
            if ($new === []) {
                return;
            }
            $changes = ['before' => $old, 'after' => $new];
        } elseif ($action === 'created') {
            $changes = ['after' => self::cleanAll($model->getAttributes())];
        } else {
            $changes = ['before' => self::cleanAll($model->getOriginal())];
        }

        self::record($action, [
            'subject_table' => $table,
            'subject_id' => $model->getKey(),
            'description' => self::describe($model),
            'changes' => $changes,
        ]);
    }

    private static function describe(Model $model): ?string
    {
        foreach (['school_name', 'schoolgroup_name', 'activity_name', 'branch_name', 'email', 'name', 'jow_name', 'title'] as $field) {
            $v = $model->getAttribute($field);
            if (is_string($v) && $v !== '') {
                return $v;
            }
        }

        return null;
    }

    private static function cleanAll(array $attrs): array
    {
        $out = [];
        foreach ($attrs as $k => $v) {
            if (in_array($k, ['updated_at', 'created_at'], true)) {
                continue;
            }
            $out[$k] = self::clean($k, $v);
        }

        return $out;
    }

    private static function clean(string $key, mixed $value): mixed
    {
        if (in_array($key, self::SECRET_FIELDS, true)) {
            return '***';
        }
        if (is_string($value) && mb_strlen($value) > 300) {
            return mb_substr($value, 0, 300).'... (ตัด)';
        }

        return $value;
    }
}
