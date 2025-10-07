<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Auth\PlainTextUserProvider;

class PlainTextAuthProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Auth::provider('plain-text-eloquent', function ($app, array $config) {
            return new PlainTextUserProvider($app['hash'], $config['model']);
        });
    }
}
