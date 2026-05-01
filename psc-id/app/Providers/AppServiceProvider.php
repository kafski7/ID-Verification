<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Rate limiter for the public QR verification API (backed by Redis via cache driver)
        RateLimiter::for('api-verify', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip())->response(function () {
                return response()->json([
                    'valid'  => false,
                    'reason' => 'Too many requests. Please try again later.',
                ], 429);
            });
        });
    }
}
