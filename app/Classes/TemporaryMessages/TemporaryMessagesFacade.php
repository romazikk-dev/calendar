<?php

namespace App\Classes\TemporaryMessages;

use Illuminate\Support\Facades\Facade;

class TemporaryMessagesFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'temp_msgs';
    }
    
}