<?php

namespace App\Classes\Helper\Helpers;

class Arr{
    
    function isWithAllNumericValues($arr){
        if(!is_array($arr))
            return false;
        foreach($arr as $k => $v){
            if(!is_numeric($v))
                return false;
        }
        return true;
    }
    
    function isWithAllStrValues($arr){
        if(!is_array($arr))
            return false;
        foreach($arr as $k => $v){
            if(!is_string($v))
                return false;
        }
        return true;
    }
    
}