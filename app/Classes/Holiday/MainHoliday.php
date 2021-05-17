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
    
    protected $validation_rules = [
        //Holiday rules
        'holiday_title.*' => 'required|max:255',
        'holiday_description.*' => 'max:1000',
        'holiday_from.*' => 'required|max:255|regex:/\d{2}-\d{2}-\d{4}/i',
        'holiday_to.*' => 'required|max:255|regex:/\d{2}-\d{2}-\d{4}/i',
    ];
    
    protected function getAllNulls(){
        return Holiday::where([
            'holidayable_type' => null,
            'holidayable_id' => null
        ])->get();
    }
    
    protected function hasOld(){
        $old_title = old(Fields::TITLE);
        return !empty($old_title);
    }
    
    protected function getOld(){
        if($this->hasOld()){
            ${Fields::TITLE} = old(Fields::TITLE);
            ${Fields::FROM} = old(Fields::FROM);
            ${Fields::TO} = old(Fields::TO);
            ${Fields::DESCRIPTION} = old(Fields::DESCRIPTION);
            
            $holidays = [];
            foreach(${Fields::TITLE} as $k => $v){
                $holiday = $this->getHoliday(
                    !empty(${Fields::TITLE}[$k]) ? ${Fields::TITLE}[$k] : null,
                    !empty(${Fields::FROM}[$k]) ? ${Fields::FROM}[$k] : null,
                    !empty(${Fields::TO}[$k]) ? ${Fields::TO}[$k] : null,
                    !empty(${Fields::DESCRIPTION}[$k]) ? ${Fields::DESCRIPTION}[$k] : null,
                );
                if(!empty($holiday))
                    $holidays[] = $holiday;
            }
            return $holidays;
        }
        return null;
    }
    
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
            }elseif($carbon_output_format !== false && is_string($carbon_output_format)){
                $holiday['from'] = \Carbon\Carbon::parse($from)->format($carbon_output_format);
            }else{
                // $holiday['from'] = \Carbon\Carbon::parse($from);
                $holiday['from'] = $from;
            }
        }else{
            return null;
        }
        
        if(!empty($to)){
            if($carbon_output_format === true){
                $holiday['to'] = \Carbon\Carbon::parse($to)->format('d-m-Y');
            }elseif($carbon_output_format !== false && is_string($carbon_output_format)){
                $holiday['to'] = \Carbon\Carbon::parse($to)->format($carbon_output_format);
            }else{
                // $holiday['to'] = \Carbon\Carbon::parse($to);
                $holiday['to'] = $to;
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