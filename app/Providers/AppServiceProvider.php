<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB; // 👈 Thêm dòng này
use Illuminate\Support\Facades\View;
use App\Http\Controllers\CartController;

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
        // Tắt chế độ ONLY_FULL_GROUP_BY
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        // 👇 View composer để truyền cartCount ra mọi view
        View::composer('*', function ($view) {
            $cartCount = (new CartController)->getCartCount();
            $view->with('cartCount', $cartCount);
        });
    }
}
