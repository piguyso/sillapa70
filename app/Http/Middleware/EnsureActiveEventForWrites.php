<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveEventForWrites
{
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $next($request);
        }

        $allowedRoutes = [
            'login', 'logout', 'password.email', 'password.update',
            'config.events.store', 'config.events.update', 'config.events.activate', 'config.events.destroy',
        ];

        $routeName = $request->route()?->getName();
        $path = '/'.ltrim($request->path(), '/');

        // This is a global middleware, so IIS/FastCGI can invoke it before the
        // named route is available. Keep a narrowly scoped path fallback for
        // event administration; the controller still requires systemadmin.
        $isEventAdministration = in_array($routeName, [
            'config.events.store', 'config.events.update', 'config.events.activate', 'config.events.destroy',
        ], true) || preg_match('~/(?:cpn1/)?(?:[0-9]+/)?config/events(?:/|$)~', $path);

        $isAuthentication = in_array($routeName, $allowedRoutes, true)
            || preg_match('~/(?:cpn1/)?(?:[0-9]+/)?(?:login|logout|password/(?:email|reset))(?:/|$)~', $path);

        if ($isEventAdministration || $isAuthentication) {
            return $next($request);
        }

        $event = active_event();
        if ($event && ! $event->is_active) {
            abort(423, 'การแข่งขันครั้งนี้เป็นข้อมูลย้อนหลังและเปิดดูได้อย่างเดียว');
        }

        return $next($request);
    }
}
