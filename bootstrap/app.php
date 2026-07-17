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
        $middleware->append(\App\Http\Middleware\TranslateToThaiNumbers::class);
        $middleware->alias([
            'centermiddleware' => \App\Http\Middleware\mymiddleware::class,
            'areamiddleware' => \App\Http\Middleware\areamiddleware::class,
            'check.area' => \App\Http\Middleware\CheckAreaSession::class,
            'areaschoolmiddleware' => \App\Http\Middleware\areaschoolmiddleware::class,
            'areaweb' => \App\Http\Middleware\areaweb::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
