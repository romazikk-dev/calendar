<?php

namespace App\Classes\Getter\Template;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;

// use App\Classes\Getter\Template\Template;
// use App\Models\Client as ModelClient;
// use App\Models\Template as ModelTemplate;
use App\Classes\Getter\Template\Enums\Params;
use App\Models\User;
use App\Scopes\UserScope;
use App\Classes\Getter\Classes\ParameterChecker;

class MainTemplate{
    
    protected $parameter_checker = null;
    function __construct() {
        $this->parameter_checker = new ParameterChecker();
    }
    
    protected function isParam($params, $param){
        if(empty($param) || empty($params) || !in_array($param, Params::all()) || empty($params[$param]))
            return false;
        
        if(in_array($param, [Params::ID, Params::WORKER_ID, Params::HALL_ID, Params::OWNER_ID]) &&
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