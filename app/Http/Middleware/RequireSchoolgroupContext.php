<?php

namespace App\Http\Middleware;

use App\Schoolgroup;
use Closure;
use Illuminate\Support\Facades\DB;
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

        // บัญชีโรงเรียนที่สังกัดหลายเครือข่าย (เช่น อำเภอ + กลุ่มโรงเรียน) สลับเครือข่ายที่ทำงานอยู่ได้
        if ($user && (int) $user->position === 0 && $user->school_id) {
            $allowed = DB::table('school_schoolgroup')->where('school_id', $user->school_id)->pluck('schoolgroup_id')->map(fn ($v) => (int) $v);
            $want = (int) ($request->query('network') ?: session('school_network'));
            if ($want && $allowed->contains($want)) {
                session(['school_network' => $want]);
                $schoolgroupId = $want;
                $user->setAttribute('schoolgroup', $want);
            }
        }

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
