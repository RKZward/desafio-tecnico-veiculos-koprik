<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Limite padrão (usado por throttle:api se você usar)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(120)->by(
                optional($request->user())->id ?: $request->ip()
            );
        });

        // Limite para login: 10 req/min por IP+email
        RateLimiter::for('login', function (Request $request) {
            $key = sprintf('login|%s|%s', $request->ip(), (string) $request->input('email'));
            return Limit::perMinute(10)->by($key);
        });

        // Limite para registro: 3/min + 10/h por IP
        RateLimiter::for('register', function (Request $request) {
            $ip = $request->ip();
            return [
                Limit::perMinute(3)->by("register|$ip"),
                Limit::perHour(10)->by("register|$ip"),
            ];
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
