<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\TeamMiddleware;
use App\Http\Middleware\CheckSession;
use Illuminate\Http\Middleware\HandleCors;

// use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        channels: __DIR__.'/../routes/channels.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend('check.session', CheckSession::class);
        $middleware->append(HandleCors::class);
        $middleware->statefulApi();


        //  $middleware->append(HandleCors::class);
        $middleware->alias([
            'team' => TeamMiddleware::class,
            // 'check.session' => CheckSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
