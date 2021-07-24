<?php

namespace App\Classes\Filter;

use App\Classes\Filter\Enums\Items;

class MainFilter{
    
    protected $cookie_name = 'dashboard_calendar-filters';
    
    public function getCookieName(){
        return $this->cookie_name;
    }
    
    protected function isFilter($filters, $filter){
        if(empty($filters) || empty($filters[$filter]))
            return false;
        
        $val = $filters[$filter];
        
        if(in_array($filter, [
            Items::HALL, Items::WORKER, Items::TEMPLATE, Items::CLIENT
        ]) && !\Helper::arr()->isWithAllNumericValues($val))
            return false;
        
        if($filter === Items::DURATION && !\DurationRange::isRight($val))
            return false;
        
        return true;
    }
    
}