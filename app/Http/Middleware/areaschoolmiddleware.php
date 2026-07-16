<?php

namespace App\Http\Middleware;

use Closure;
use App\Systemactives;
use Auth;
class areaschoolmiddleware
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

        $systemstatus = Systemactives::where('schoolgroup',Auth::user()->schoolgroup)->get()->first();
       // dd($systemstatus);
        if (Auth::user()->position == '0' && $systemstatus->status == 1)
        {
          return response()->view('errors.systemstop');
        }
        else
        {
          
          return $next($request);
        }

    }
}
