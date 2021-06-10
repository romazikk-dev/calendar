<?php

namespace App\Classes\Getter;

use Illuminate\Support\Facades\Facade;

class GetterFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'getter';
    }
    
}