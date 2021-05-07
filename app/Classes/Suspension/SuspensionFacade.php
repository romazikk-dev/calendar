<?php

namespace App\Classes\Suspension;

use Illuminate\Support\Facades\Facade;

class SuspensionFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'suspension';
    }
    
}