<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Calendar\Calendar;

class CalendarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('calendar',function(){
            return new Calendar();
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
