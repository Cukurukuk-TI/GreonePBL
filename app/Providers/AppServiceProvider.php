<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Keranjang;
use App\Models\Testimoni;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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
    View::composer('*', function ($view) {
        if (Auth::check()) {
            $uniqueProductCount = Keranjang::where('user_id', Auth::id())
                ->distinct('produk_id')
                ->count('produk_id');
        } else {
            $uniqueProductCount = 0;
        }

        $view->with('uniqueProductCount', $uniqueProductCount);
    });

    View::composer('layouts.admindashboard', function ($view) {
        $view->with('testimoniBaru', Testimoni::latest()->take(5)->get());
    });

    }
}
