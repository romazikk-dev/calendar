<?php
namespace App\Classes\Traits;

// App\Classes\Traits\Enumerable

trait Carbonable{
    
    function carbonGetDiffBetweenTwoInstances($instance_1, $instance_2, $in = 'minutes'){
        $diff = $instance_1->diff($instance_2);
        
        if($in == 'minutes'){
            $out = 0;
            $m = $diff->i;
            $h = $diff->h;
            if($h != 0)
                $out = $h * 60;
            return $out + $m;
        }
        
        return $diff;
    }
    
}