<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\DurationRange\DurationRange;

class DurationRangeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('duration_range',function(){
            return new DurationRange();
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
