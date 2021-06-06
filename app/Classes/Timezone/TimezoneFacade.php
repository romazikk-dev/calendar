<?php

namespace App\Classes\Timezone;

use Illuminate\Support\Facades\Facade;

class TimezoneFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'timezone';
    }
    
}