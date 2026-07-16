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
      //  if (Auth::user()->position != '1') {
            //return view('errors.notpermis');
       //     return response()->view('errors.notpermis');
       // }elseif (Auth::user()->position != '1'){

      //  }


      if (Auth::user()->position == '1')
      {
        return $next($request);
      }
      elseif (Auth::user()->position == '10')
      {
        return $next($request);
      }
      else
      {
        return response()->view('errors.notpermis');
      }

        
    }
}
