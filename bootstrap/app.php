<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureAdmin;

// Paksa agar cache driver otomatis jadi 'file' jika belum diset atau nyangkut ke database
$_ENV['CACHE_DRIVER'] = $_ENV['CACHE_DRIVER'] ?? 'file';
putenv('CACHE_DRIVER=file');

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(web: __DIR__.'/../routes/web.php', commands: __DIR__.'/../routes/console.php', health: '/up')
    ->withMiddleware(function (Middleware $middleware) {
        // Percayai semua proxy Render agar HTTPS dan Cookie CSRF sinkron
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: ['payments/midtrans/notification']);
        $middleware->alias(['admin' => EnsureAdmin::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();