<?php

namespace App\Classes\Statistic;

use Illuminate\Support\Facades\Facade;

class StatisticFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'statistic';
    }
    
}