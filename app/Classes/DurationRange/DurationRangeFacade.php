<?php

namespace App\Classes\DurationRange;

use Illuminate\Support\Facades\Facade;

class DurationRangeFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'duration_range';
    }
    
}