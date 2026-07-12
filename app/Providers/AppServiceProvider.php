<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Paksa form Laravel pakai HTTPS kalau lagi di server Railway
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
    }
}