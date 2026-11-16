<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NetworkRegistrationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! in_array((int) $request->user()->position, [0, 5, 10], true)) {
            abort(403, 'ไม่มีสิทธิ์จัดการข้อมูลผู้สมัครระดับเครือข่าย');
        }

        return $next($request);
    }
}
