<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Suspension\Suspension;

class SuspensionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('suspension',function(){
            return new Suspension();
        });
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
