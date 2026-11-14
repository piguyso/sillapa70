<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StripEventRouteParameter
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->route()?->forgetParameter('eventNumber');

        return $next($request);
    }
}
