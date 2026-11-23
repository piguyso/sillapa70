<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * ติดตามผู้เข้าชมด้วย cookie visitor_id (30 วัน)
 *  - visitor_logs   : เฉพาะคนที่ active ล่าสุด ใช้นับ "ออนไลน์" (ลบทิ้งเมื่อเงียบเกิน 15 นาที)
 *  - visitor_totals : ผู้เข้าชมไม่ซ้ำสะสมตลอดกาล ใช้นับ "ผู้เข้าชมทั้งหมด"
 * ไม่นับบอท/เครื่องมือตรวจสอบและการเรียกจากเครื่องเซิร์ฟเวอร์เอง
 */
class TrackVisitors
{
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|curl|wget|python|scrapy|httpclient|java\/|go-http|okhttp|facebookexternalhit|headless|lighthouse|monitor|uptime|preview|checker|scanner|axios|node-fetch|postman/i';

    public function handle(Request $request, Closure $next): Response
    {
        $visitorId = (string) $request->cookie('visitor_id');
        $isNewVisitor = ! preg_match('/^[0-9a-f-]{36}$/i', $visitorId);
        if ($isNewVisitor) {
            $visitorId = Str::uuid()->toString();
        }

        $isPing = $request->headers->get('X-Visitor-Ping') === '1';
        $countable = $request->isMethod('get') && ($isPing || ! $request->ajax()) && $this->isHuman($request);

        if ($countable) {
            $this->touch($request, $visitorId);
        }

        $response = $next($request);

        if ($isNewVisitor && $countable) {
            $response->headers->setCookie(cookie('visitor_id', $visitorId, 60 * 24 * 30, '/', null, false, false));
        }

        return $response;
    }

    private function isHuman(Request $request): bool
    {
        $ua = (string) $request->userAgent();
        if ($ua === '' || preg_match(self::BOT_PATTERN, $ua)) {
            return false;
        }

        return ! in_array($request->ip(), ['127.0.0.1', '::1'], true);
    }

    private function touch(Request $request, string $visitorId): void
    {
        // อัปเดตอย่างมากทุก 60 วินาทีต่อ visitor
        $throttleKey = 'visitor_touch:'.$visitorId;
        if (! Cache::add($throttleKey, 1, 60)) {
            return;
        }

        try {
            $now = now();
            DB::table('visitor_logs')->upsert([[
                'visitor_id' => $visitorId,
                'ip_address' => $request->ip() ?: 'unknown',
                'session_id' => $request->hasSession() ? $request->session()->getId() : '',
                'url' => substr($request->fullUrl(), 0, 255),
                'last_activity' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]], ['visitor_id'], ['ip_address', 'session_id', 'url', 'last_activity', 'updated_at']);

            DB::table('visitor_totals')->insertOrIgnore(['visitor_id' => $visitorId, 'first_seen_at' => $now]);

            // ล้างรายการเงียบเกิน 15 นาที อย่างมากทุก 5 นาที (ไม่ต้องลบทุกครั้งที่มีคนเข้า)
            if (Cache::add('visitor_prune', 1, 300)) {
                DB::table('visitor_logs')->where('last_activity', '<', $now->copy()->subMinutes(15))->delete();
            }
        } catch (\Throwable $e) {
            // ห้ามให้ระบบนับผู้เข้าชมทำให้หน้าเว็บล่ม
        }
    }
}
