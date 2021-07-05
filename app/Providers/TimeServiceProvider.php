<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Time\Time;

class TimeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('time',function(){
            return new Time();
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
