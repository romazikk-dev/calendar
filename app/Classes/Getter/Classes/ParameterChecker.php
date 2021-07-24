<?php

namespace App\Classes\Getter\Classes;

// use App\Classes\Getter\Classes\ParameterChecker;

class ParameterChecker{
    
    function isArrayWithAllNumericValues($val){
        if(!is_array($val))
            return false;
        foreach($val as $k => $v){
            if(!is_numeric($v))
                return false;
        }
        return true;
    }
    
    function isArrayWithAllStrValues($val){
        if(!is_array($val))
            return false;
        foreach($val as $k => $v){
            if(!is_string($v))
                return false;
        }
        return true;
    }
    
}