<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $value = request()->cookie('lang');
        // $encrypter = app(\Illuminate\Contracts\Encryption\Encrypter::class);
        // 
        // // decrypt
        // $decryptedValue = $encrypter->decrypt($value);
        
        $locale = request()->cookie('admin_lang');
        if(empty($locale))
            return;
        
        $lang = explode('|', \Crypt::decrypt($locale, false))[1];
        \App::setlocale($lang);
    }
}
