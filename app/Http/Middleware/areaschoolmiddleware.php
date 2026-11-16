<?php

namespace App\Http\Middleware;

use Closure;

class areaschoolmiddleware
{
    public function handle($request, Closure $next)
    {
        // Compatibility alias. CompetitionAccessMiddleware owns access rules.
        return $next($request);
    }
}
