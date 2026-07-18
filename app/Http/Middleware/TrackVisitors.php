<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('get') && !$request->ajax()) {
            $lastUpdated = session('last_activity_updated');
            $currentTime = time();

            // Only update database at most once every 60 seconds per session
            if (!$lastUpdated || ($currentTime - $lastUpdated) >= 60) {
                try {
                    $ip = $request->ip();
                    $sessionId = session()->getId();
                    $url = $request->fullUrl();

                    DB::table('visitor_logs')->updateOrInsert(
                        ['session_id' => $sessionId],
                        [
                            'ip_address' => $ip ?: 'unknown',
                            'url' => substr($url, 0, 255),
                            'last_activity' => now(),
                            'updated_at' => now(),
                            'created_at' => DB::raw('COALESCE(created_at, NOW())')
                        ]
                    );

                    session(['last_activity_updated' => $currentTime]);
                } catch (\Exception $e) {
                    // Ignore database errors to not interrupt the request
                }
            }
        }

        return $next($request);
    }
}
