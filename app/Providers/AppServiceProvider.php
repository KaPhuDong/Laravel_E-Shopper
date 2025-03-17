<?php

namespace App\Providers;

use App\Models\TypeProduct;
use Illuminate\Support\ServiceProvider;
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
        View::composer('Component.header', function ($view) {
            $loai_sp = TypeProduct::all();
            $view->with('loai_sp', $loai_sp);
        });
    }
}
