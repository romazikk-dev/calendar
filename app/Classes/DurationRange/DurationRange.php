<?php
namespace App\Classes\DurationRange;

use App\Classes\DurationRange\Enums\GetParams;

class DurationRange extends MainDurationRange{
    
    public function get($param = null){
        if(is_null($param) || !in_array(($param = strtolower($param)), GetParams::all()))
            return $this->range;
        
        if($param === GetParams::START)
            return $this->range['start'];
            
        if($param === GetParams::END)
            return $this->range['end'];
    }
    
    public function isRight($range){
        if(!is_array($range) ||
        (empty($range['start']) && empty($range['end'])))
            return false;
            
        if(
            (!empty($range['start']) && !is_numeric($range['start'])) ||
            (!empty($range['end']) && !is_numeric($range['end']))
        )
            return false;
            
        if(!empty($range['start']) && !empty($range['end']) && $range['start'] > $range['end'])
            return false;
            
        return true;
    }
    
}