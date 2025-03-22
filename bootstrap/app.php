<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\RateLimiting;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Tambahkan ini
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'throttle:api', // Tetap bisa menggunakan alias dari middleware bawaan Laravel
        ]);
    })
    ->withProviders([
        App\Providers\RateLimitingServiceProvider::class, // Daftarkan rate limiter
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();