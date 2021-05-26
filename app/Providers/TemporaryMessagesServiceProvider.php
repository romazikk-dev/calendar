<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\TemporaryMessages\TemporaryMessages;

class TemporaryMessagesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('temp_msgs',function(){
            return new TemporaryMessages();
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
