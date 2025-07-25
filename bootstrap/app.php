<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Application as AppConfig;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
         $middleware->alias([
            'admin'=> \App\Http\Middleware\AdminMiddleware::class,
            'admin.timeout' => \App\Http\Middleware\AdminSessionTimeout::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/midtrans/*' // URL webhook Anda
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
