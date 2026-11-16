<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            return $next($request);
        }

        $allowed = $request->routeIs('password.change-required.*', 'logout')
            || preg_match('~/(?:cpn1/)?(?:[0-9]+/)?(?:password/change-required|logout)(?:/|$)~', '/'.$request->path());

        $sessionVersion = (int) $request->session()->get('auth_password_version', $user->password_version);
        if (! $allowed && $sessionVersion !== (int) $user->password_version) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'รหัสผ่านของบัญชีถูกรีเซ็ต กรุณาเข้าสู่ระบบใหม่');
        }

        if (! $allowed && $user->must_change_password) {
            return redirect()->to(event_url('password/change-required'));
        }

        return $next($request);
    }
}

