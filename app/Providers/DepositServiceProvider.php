<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DepositServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('deposit', 'App\Services\Deposit');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
