<?php

namespace App\Classes\Helper;

use Illuminate\Support\Facades\Facade;

class HelperFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'helper';
    }
    
}