<?php

namespace App\Classes\PhonePicker;

use Illuminate\Support\Facades\Facade;

class PhoneFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'phonepicker';
    }
    
}