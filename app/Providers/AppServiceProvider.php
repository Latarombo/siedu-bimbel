<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('auth', function (Request $request) {
            if ($this->app->environment('testing')) {
                return Limit::perMinute(1000)->by($request->ip());
            }

            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return response('Terlalu banyak percobaan. Coba lagi dalam 1 menit.', 429);
            });
        });

        RateLimiter::for('password-reset', function (Request $request) {
            if ($this->app->environment('testing')) {
                return Limit::perMinute(1000)->by($request->ip());
            }

            return Limit::perMinute(3)->by($request->ip())->response(function () {
                return response('Terlalu banyak permintaan reset password. Coba lagi nanti.', 429);
            });
        });

        if (empty(config('app.key'))) {
            throw new \RuntimeException('APP_KEY is not set. Run: php artisan key:generate');
        }
    }
}
