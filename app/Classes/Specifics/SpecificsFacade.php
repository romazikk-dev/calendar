<?php
namespace App\Classes\Specifics;

use Illuminate\Support\Facades\Facade;

class SpecificsFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'specifics';
    }
    
}