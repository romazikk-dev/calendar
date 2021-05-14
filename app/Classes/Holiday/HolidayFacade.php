<?php

namespace App\Classes\Holiday;

use Illuminate\Support\Facades\Facade;

class HolidayFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'holiday';
    }
    
}