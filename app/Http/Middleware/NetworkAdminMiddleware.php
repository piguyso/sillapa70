<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NetworkAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! in_array((int) $request->user()->position, [5, 10], true)) {
            abort(403, 'สงวนสิทธิ์สำหรับผู้ดูแลเครือข่ายหรือผู้ดูแลระบบ');
        }

        return $next($request);
    }
}
