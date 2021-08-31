<?php

namespace App\Classes\Getter\Worker;

use App\Classes\Getter\Worker\Enums\Params;
use App\Classes\Getter\Classes\ParameterChecker;

class MainWorker{
    
    protected $parameter_checker = null;
    function __construct() {
        $this->parameter_checker = new ParameterChecker();
    }
    
    protected function isParam($params, $param){
        // var_dump(222222);
        // die();
        // var_dump($params);
        // var_dump($param);
        // var_dump(Params::all());
        // var_dump(in_array($param, Params::all()));
        if(empty($param) || empty($params) || !in_array($param, Params::all()) || empty($params[$param]))
            return false;
        
        // var_dump(222222);
        
        if(in_array($param, [Params::ID, Params::TEMPLATE_ID, Params::HALL_ID, Params::OWNER_ID]) &&
        !$this->parameter_checker->isArrayWithAllNumericValues($params[$param]) &&
        !is_numeric($params[$param]))
            return false;
            
        if(strtolower($param) == Params::WITH &&
        !$this->parameter_checker->isArrayWithAllStrValues($params[$param]) &&
        !is_string($params[$param]))
            return false;
        
        return true;
    }
    
}