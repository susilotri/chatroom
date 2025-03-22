<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60); // Maksimal 60 request per menit per IP
        });
    }
}
