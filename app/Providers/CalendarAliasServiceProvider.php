<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\CalendarAlias\Alias;

class CalendarAliasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('calendar_alias',function(){
            return new Alias();
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
