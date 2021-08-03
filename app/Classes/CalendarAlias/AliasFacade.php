<?php

namespace App\Classes\CalendarAlias;

use Illuminate\Support\Facades\Facade;

class AliasFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'calendar_alias';
    }
    
}