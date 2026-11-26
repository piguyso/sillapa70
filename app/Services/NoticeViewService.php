<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** สถิติการเปิดอ่านประกาศ: บันทึก (กันนับซ้ำ 30 นาที, ข้ามบอท) และสรุปให้แอดมิน */
class NoticeViewService
{
    public const DEDUP_MINUTES = 30;

    public static function isBot(?string $ua): bool
    {
        return $ua === null || $ua === '' || (bool) preg_match('/bot|crawl|spider|slurp|curl|wget|python|preview|facebookexternalhit/i', $ua);
    }

    public static function record(Request $request, int $noticeId, string $source = 'district'): bool
    {
        try {
            if (!Schema::hasTable('notice_views')) {
                return false;
            }
            $ua = (string) $request->userAgent();
            if (self::isBot($ua)) {
                return false;
            }
            $userId = auth()->id();
            $hash = hash('sha256', $userId ? 'u'.$userId : 'g'.$request->ip().'|'.$ua);
            $dup = DB::table('notice_views')->where('notice_id', $noticeId)->where('visitor_hash', $hash)
                ->where('viewed_at', '>=', now()->subMinutes(self::DEDUP_MINUTES))->exists();
            if ($dup) {
                return false;
            }
            DB::table('notice_views')->insert([
                'notice_id' => $noticeId, 'user_id' => $userId, 'visitor_hash' => $hash,
                'source' => $source, 'user_agent' => mb_substr($ua, 0, 255), 'viewed_at' => now(),
            ]);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function stats(int $noticeId, int $days = 14): array
    {
        $empty = ['total' => 0, 'unique' => 0, 'today' => 0, 'week' => 0, 'daily' => [], 'recent' => collect()];
        try {
            if (!Schema::hasTable('notice_views')) {
                return $empty;
            }
            $q = fn () => DB::table('notice_views')->where('notice_id', $noticeId);
            $perDay = $q()->where('viewed_at', '>=', now()->subDays($days - 1)->startOfDay())
                ->selectRaw('DATE(viewed_at) d, COUNT(*) c')->groupBy('d')->pluck('c', 'd');
            $daily = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $d = now()->subDays($i)->toDateString();
                $daily[$d] = (int) ($perDay[$d] ?? 0);
            }
            return [
                'total' => $q()->count(),
                'unique' => $q()->distinct()->count('visitor_hash'),
                'today' => $q()->where('viewed_at', '>=', now()->startOfDay())->count(),
                'week' => $q()->where('viewed_at', '>=', now()->subDays(6)->startOfDay())->count(),
                'daily' => $daily,
                'recent' => $q()->leftJoin('users', 'users.id', '=', 'notice_views.user_id')
                    ->orderByDesc('notice_views.id')->limit(10)
                    ->get(['notice_views.viewed_at', 'notice_views.source', 'users.name as user_name']),
            ];
        } catch (\Throwable $e) {
            return $empty;
        }
    }

    public static function totals(array $noticeIds): array
    {
        try {
            if (!$noticeIds || !Schema::hasTable('notice_views')) {
                return [];
            }
            return DB::table('notice_views')->whereIn('notice_id', $noticeIds)
                ->selectRaw('notice_id, COUNT(*) c')->groupBy('notice_id')->pluck('c', 'notice_id')->all();
        } catch (\Throwable $e) {
            return [];
        }
    }
}
