<?php

namespace App\Classes\Holiday;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Booking;
// use App\Models\User;
// use App\Models\Hall;
// use App\Models\Template;
use App\Models\Holiday;
// use App\Exceptions\Api\Calendar\BadRangeException;
// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
use App\Classes\Holiday\Enums\Fields;

class MainHoliday{
    
    protected function getHoliday($title, $from, $to, $description, $carbon_output_format = false){
        
        $holiday = [];
        
        if(!empty($title)){
            $holiday['title'] = $title;
        }else{
            return null;
        }
        
        if(!empty($from)){
            if($carbon_output_format === true){
                $holiday['from'] = \Carbon\Carbon::parse($from)->format('d-m-Y');
            }else{
                $holiday['from'] = \Carbon\Carbon::parse($from);
            }
        }else{
            return null;
        }
        
        if(!empty($to)){
            if($carbon_output_format === true){
                $holiday['to'] = \Carbon\Carbon::parse($to)->format('d-m-Y');
            }else{
                $holiday['to'] = \Carbon\Carbon::parse($to);
            }
        }else{
            return null;
        }
        
        if(!empty($description)){
            $holiday['description'] = $description;
        }else{
            $holiday['description'] = null;
        }
        
        return !empty($holiday) ? $holiday : null;
        
    }
    
}