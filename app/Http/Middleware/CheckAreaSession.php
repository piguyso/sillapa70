<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckAreaSession
{
    public function handle($request, Closure $next)
    {
        // ถ้าไม่มี session area → redirect กลับหน้าแรก
        if (!Session::has('area')) {
            return redirect('/');
        }

        return $next($request);
    }
}
