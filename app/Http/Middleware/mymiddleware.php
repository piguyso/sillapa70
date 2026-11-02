<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class mymiddleware
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
      // position 1  = บันทึกคะแนน / ศูนย์
      // position 5  = เครือข่ายสถานศึกษา (แก้ได้เฉพาะรูปเกียรติบัตรของตัวเอง)
      // position 10 = ผู้ดูแลระบบ

      if (Auth::user()->position == '1')
      {
        return $next($request);
      }
      elseif (Auth::user()->position == '10')
      {
        return $next($request);
      }
      elseif (Auth::user()->position == '5')
      {
        // อนุญาตเฉพาะ GET (ดูหน้า) และ PUT/PATCH (บันทึก) — ตรวจสอบละเอียดอีกครั้งใน Controller
        return $next($request);
      }
      else
      {
        return response()->view('errors.notpermis');
      }

        
    }
}
