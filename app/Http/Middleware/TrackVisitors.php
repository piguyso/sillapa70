<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     * ติดตามผู้เข้าชมโดยใช้ Cookie visitor_id แบบถาวร (30 วัน)
     * เพื่อป้องกันการนับซ้ำเมื่อ reload หน้า หรือเปิดหลาย tab
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ดึง visitor_id จาก cookie ถ้าไม่มีให้สร้างใหม่
        $visitorId = $request->cookie('visitor_id');
        $isNewVisitor = false;

        if (!$visitorId) {
            $visitorId = Str::uuid()->toString();
            $isNewVisitor = true;
        }

        if ($request->isMethod('get') && !$request->ajax()) {
            $lastUpdated = session('last_activity_updated');
            $currentTime = time();

            // อัปเดต DB อย่างมากทุก 60 วินาที ต่อ visitor_id หนึ่งคน
            if (!$lastUpdated || ($currentTime - $lastUpdated) >= 60) {
                try {
                    $ip = $request->ip();
                    $url = $request->fullUrl();

                    // upsert โดยใช้ visitor_id (cookie) เป็น key — ไม่ใช่ session_id
                    DB::table('visitor_logs')->updateOrInsert(
                        ['visitor_id' => $visitorId],
                        [
                            'ip_address'   => $ip ?: 'unknown',
                            'session_id'   => session()->getId(),
                            'url'          => substr($url, 0, 255),
                            'last_activity'=> now(),
                            'updated_at'   => now(),
                            'created_at'   => DB::raw('COALESCE(created_at, NOW())'),
                        ]
                    );

                    // ลบ record เก่า > 15 นาที ออกจาก DB อัตโนมัติ
                    DB::table('visitor_logs')
                        ->where('last_activity', '<', now()->subMinutes(15))
                        ->delete();

                    session(['last_activity_updated' => $currentTime]);
                } catch (\Exception $e) {
                    // Ignore database errors to not interrupt the request
                }
            }
        }

        $response = $next($request);

        // Set cookie visitor_id อายุ 30 วัน (ถ้าเป็น visitor ใหม่ หรือ cookie ใกล้หมดอายุ)
        if ($isNewVisitor) {
            $response->headers->setCookie(
                cookie('visitor_id', $visitorId, 60 * 24 * 30, '/', null, false, false)
            );
        }

        return $response;
    }
}

