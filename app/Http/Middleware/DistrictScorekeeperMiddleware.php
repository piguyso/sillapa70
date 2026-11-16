<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DistrictScorekeeperMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! in_array((int) $request->user()->position, [1, 10], true)) {
            abort(403, 'ไม่มีสิทธิ์จัดการผลการแข่งขันระดับเขตพื้นที่');
        }

        return $next($request);
    }
}
