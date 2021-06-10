<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Getter\Getter;

class GetterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('getter',function(){
            return new Getter();
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
