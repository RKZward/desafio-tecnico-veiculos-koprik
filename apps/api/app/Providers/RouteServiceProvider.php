<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('login', fn($r) => [Limit::perMinute(5)->by($r->ip()), Limit::perMinute(5)->by($r->input('email'))]);
        RateLimiter::for('register', fn($r) => [Limit::perMinute(3)->by($r->ip()), Limit::perMinute(3)->by($r->input('email'))]);
        RateLimiter::for('uploads', fn($r) => [Limit::perMinute(20)->by(optional($r->user())->id ?: $r->ip())]);

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }
}
