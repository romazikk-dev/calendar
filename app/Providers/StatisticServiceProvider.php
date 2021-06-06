<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Statistic\Statistic;

class StatisticServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('statistic',function(){
            return new Statistic();
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
