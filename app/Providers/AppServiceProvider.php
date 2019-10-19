<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // old version of mysql on xampp
        if (!$this->app->environment('production')) {
            Schema::defaultStringLength(191);
        }

        Route::model('account', Account::class);
        Route::model('transaction', Transaction::class);
    }
}
