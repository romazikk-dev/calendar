<?php
namespace App\Classes\Statistic;

use App\Classes\Statistic\Statistics\MainPageStatistic;

class Statistic extends MainStatistic{
    
    public function getMainPageStatistic(){
        return (new MainPageStatistic($this))->get();
    }
    
}