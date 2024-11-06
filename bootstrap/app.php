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
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        // ...
    )
    ->withRouting(
        api: __DIR__.'/../routes/admin.php',
        apiPrefix: 'admin',
        // ...
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'app-active' => \App\Http\Middleware\AppActive::class,
            'client-valid' => \App\Http\Middleware\ClientVersionValid::class,
            'checkAdmin' => \App\Http\Middleware\IsAdmin::class,
            'setLocalLang' => \App\Http\Middleware\Localization::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
