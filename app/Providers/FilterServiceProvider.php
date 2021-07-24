<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Filter\Filter;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('filter',function(){
            return new Filter();
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
