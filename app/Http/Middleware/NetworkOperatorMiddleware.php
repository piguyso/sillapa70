<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NetworkOperatorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! in_array((int) $request->user()->position, [1, 5, 10], true)) {
            abort(403, 'ไม่มีสิทธิ์จัดการกรรมการระดับเครือข่าย');
        }

        return $next($request);
    }
}
