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
        //
    if (env('APP_ENV') === 'local' || $this->app->environment('local')) {
        // Baris ini akan memaksa URL yang dihasilkan oleh helper `route()`
        // untuk menggunakan HTTPS saat aplikasi dijalankan melalui Ngrok atau sejenisnya.
        // URL::forceScheme('https');
        }
    }
}
