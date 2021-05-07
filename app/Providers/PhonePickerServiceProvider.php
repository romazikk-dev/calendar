<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\PhonePicker\Phone;

class PhonePickerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('phonepicker',function(){
            return new Phone();
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
