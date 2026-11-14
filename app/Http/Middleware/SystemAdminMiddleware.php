<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SystemAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || (int) $request->user()->position !== 10) {
            abort(403, 'การทำรายการนี้สงวนไว้สำหรับผู้ดูแลระบบเท่านั้น');
        }

        return $next($request);
    }
}
