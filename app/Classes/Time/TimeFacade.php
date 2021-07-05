<?php

namespace App\Classes\Time;

use Illuminate\Support\Facades\Facade;

class TimeFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'time';
    }
    
}