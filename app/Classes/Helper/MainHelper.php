<?php

namespace App\Classes\Helper;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;
// use App\Models\Worker;

use App\Classes\Helper\Helpers\Arr;

class MainHelper{
    
    protected $arr_helper;
    
    function __construct() {
        $this->arr_helper = new Arr();
    }
    
    public function arr(){
        return $this->arr_helper;
    }
    
}