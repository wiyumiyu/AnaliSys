<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRol;
use App\Http\Middleware\SetBitacoraContext;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

    ->withMiddleware(function (Middleware $middleware) {

        // 🔹 Middleware de bitácora SOLO para web (después de la sesión)
        $middleware->appendToGroup(
            'web',
            SetBitacoraContext::class
        );

        // 🔹 Alias de roles
        $middleware->alias([
            'rol' => CheckRol::class,
        ]);
    })

    ->create();
