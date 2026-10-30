<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureAdmin;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Percayai semua proxy Render agar HTTPS dan Cookie CSRF sinkron
        $middleware->trustProxies(at: '*');

        // Pengecualian proteksi CSRF untuk payment gateway dan semua endpoint API Android
        $middleware->validateCsrfTokens(except: [
            'payments/midtrans/notification',
            'api/*',
            'login',
        ]);

        $middleware->alias(['admin' => EnsureAdmin::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();