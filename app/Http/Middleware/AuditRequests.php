<?php

namespace App\Http\Middleware;

use App\Services\AuditLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * บันทึกการกระทำระดับคำขอ: ทุกคำขอที่เปลี่ยนข้อมูล (POST/PUT/PATCH/DELETE)
 * และการส่งออก/พิมพ์/ดาวน์โหลดแบบ GET — ไม่เก็บค่าที่กรอก เก็บเฉพาะชื่อฟิลด์และพารามิเตอร์เส้นทาง
 */
class AuditRequests
{
    private const IGNORED = ['visitor-ping', 'up', 'login', 'logout'];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            if ($this->shouldLog($request)) {
                $route = $request->route();
                AuditLogger::record('request', [
                    'description' => $request->method().' /'.ltrim($request->path(), '/').($route?->getName() ? ' ['.$route->getName().']' : ''),
                    'status' => $response->getStatusCode(),
                    'changes' => [
                        'route' => $route?->getName() ?? $route?->uri(),
                        'params' => $route ? array_map(fn ($v) => is_scalar($v) ? $v : null, $route->parametersWithoutNulls()) : [],
                        'fields' => array_values(array_diff(array_keys($request->except(['_token', '_method'])), AuditLogger::SECRET_FIELDS)),
                    ],
                ]);
            }
        } catch (\Throwable $e) {
            // ไม่ให้การบันทึก log ทำให้งานหลักล้ม
        }

        return $response;
    }

    private function shouldLog(Request $request): bool
    {
        $path = trim($request->path(), '/');
        foreach (self::IGNORED as $ignored) {
            if (str_ends_with($path, $ignored)) {
                return false;
            }
        }

        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return true;
        }

        // GET ที่เป็นการส่งออก/พิมพ์/ดาวน์โหลด
        return (bool) preg_match('~(export|print|pdf|excel|download|backup|template)~i', $path);
    }
}
