<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRol;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    // ✅ EXCEPCIONES (ESTO ES LO QUE FALTABA)
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

    // ✅ MIDDLEWARES
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'rol' => CheckRol::class,
        ]);
    })

    ->create();
