<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->append(\App\Http\Middleware\SetEventContext::class);
        $middleware->web(append: [
            \App\Http\Middleware\SetEventContext::class,
            \App\Http\Middleware\RequirePasswordChange::class,
            \App\Http\Middleware\AuditRequests::class,
        ]);
        $middleware->append(\App\Http\Middleware\EnsureActiveEventForWrites::class);
        $middleware->append(\App\Http\Middleware\TranslateToThaiNumbers::class);
        $middleware->append(\App\Http\Middleware\ApplyNetworkLabel::class);
        $middleware->append(\App\Http\Middleware\TrackVisitors::class);
        $middleware->alias([
            'centermiddleware' => \App\Http\Middleware\mymiddleware::class,
            'areamiddleware' => \App\Http\Middleware\areamiddleware::class,
            'check.area' => \App\Http\Middleware\CheckAreaSession::class,
            'areaschoolmiddleware' => \App\Http\Middleware\areaschoolmiddleware::class,
            'areaweb' => \App\Http\Middleware\areaweb::class,
            'systemadmin' => \App\Http\Middleware\SystemAdminMiddleware::class,
            'strip.event.parameter' => \App\Http\Middleware\StripEventRouteParameter::class,
            'schoolgroup.context' => \App\Http\Middleware\RequireSchoolgroupContext::class,
            'score.network' => \App\Http\Middleware\NetworkScorekeeperMiddleware::class,
            'score.district' => \App\Http\Middleware\DistrictScorekeeperMiddleware::class,
            'networkadmin' => \App\Http\Middleware\NetworkAdminMiddleware::class,
            'registration.network' => \App\Http\Middleware\NetworkRegistrationMiddleware::class,
            'operator.network' => \App\Http\Middleware\NetworkOperatorMiddleware::class,
            'competition.access' => \App\Http\Middleware\CompetitionAccessMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
