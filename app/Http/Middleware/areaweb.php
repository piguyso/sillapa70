<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // เพิ่มการใช้งาน DB Facade
class areaweb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // ดึงข้อมูล status จาก id 15
        $system = DB::table('systemactives')->where('id', 15)->first();

        // เงื่อนไข: ถ้าหาเจอ และ status เป็น 1 ให้บล็อกทันที
        if ($system && $system->status == 1) {
            return response()->view('errors.areaweb');
        }

        // ถ้าไม่ใช่ 1 (เช่น เป็น 0, null หรือไม่มี id นี้) ให้ผ่านไปได้ทุกคน
        return $next($request);
  
    }
}
