<?php

namespace App\Http\Middleware;

use App\Schoolgroup;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSchoolgroupContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! Schoolgroup::whereKey($user->schoolgroup)->exists()) {
            abort(403, 'กรุณาเข้าสู่ระบบด้วยบัญชีที่ระบุเครือข่ายสถานศึกษา');
        }

        return $next($request);
    }
}
