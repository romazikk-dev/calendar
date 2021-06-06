<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Timezone\Timezone;

class TimezoneServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('timezone',function(){
            return new Timezone();
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
