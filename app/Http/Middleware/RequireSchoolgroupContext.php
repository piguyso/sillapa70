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
        $schoolgroupId = $user && (int) $user->position >= 10
            ? session('area')
            : $user?->schoolgroup;

        if (! $user || ! Schoolgroup::whereKey($schoolgroupId)->exists()) {
            abort(403, 'กรุณาเข้าสู่ระบบด้วยบัญชีที่ระบุเครือข่ายสถานศึกษา');
        }

        if ((int) $user->position >= 10) {
            // Admin chooses a network through session('area'). Expose that
            // context to legacy controllers/views without changing the user row.
            $user->setAttribute('schoolgroup', (int) $schoolgroupId);
        }

        $request->attributes->set('schoolgroup_id', (int) $schoolgroupId);

        return $next($request);
    }
}
