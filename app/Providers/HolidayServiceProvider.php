<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Holiday\Holiday;

class HolidayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('holiday',function(){
            return new Holiday();
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
