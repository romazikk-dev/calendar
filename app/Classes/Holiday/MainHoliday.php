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
    
    protected $key_format = 'Y_m_d';
    
    protected $validation_rules = [
        //Holiday rules
        'holiday_title.*' => 'required|max:255',
        'holiday_description.*' => 'nullable|max:1000',
        'holiday_from.*' => 'required|max:255|regex:/\d{2}-\d{2}-\d{4}/i',
        'holiday_to.*' => 'required|max:255|regex:/\d{2}-\d{2}-\d{4}/i',
        'holiday_timestamp.*' => 'required|numeric',
    ];
    
    protected function mergeHolidaysAsUnique(array $holidays_first, array $holidays_second){
        return array_unique(array_merge($holidays_first, $holidays_second));
    }
    
    public function getKeyFormat(){
        return $this->key_format;
    }
    
    public function getKeyOfCarbonInstance($carbon_instance){
        return $carbon_instance->format($this->key_format);
    }
    
    public function getNullHolidaysOfOwner(int $owner_id){
        return Holiday::where([
            'user_id' => $owner_id,
            'holidayable_type' => null,
            'holidayable_id' => null,
        ])->get();
    }
    
    public function parseHolidaysIntoArrayKey($holidays): array {
        if(empty($holidays_arr = $holidays->toArray()))
            return [];
    
        $output = [];
        foreach($holidays_arr as $holiday){
            $from_carbon = \Carbon\Carbon::parse($holiday['from']);
            $to_carbon = \Carbon\Carbon::parse($holiday['to']);
            $val = $this->getKeyOfCarbonInstance($from_carbon);
            if(!in_array($val, $output))
                $output[] = $val;
    
            for($i = 0; $i < 1000; $i++){
                $from_carbon->addDays(1);
                if($to_carbon->lt($from_carbon))
                    break;
    
                $val = $this->getKeyOfCarbonInstance($from_carbon);
                if(!in_array($val, $output))
                    $output[] = $val;
            }
        }
        
        return $output;
    }
    
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
            ${Fields::TIMESTAMP} = old(Fields::TIMESTAMP);
            
            // dd(old(Fields::TIMESTAMP));
            
            $holidays = [];
            foreach(${Fields::TITLE} as $k => $v){
                $holiday = $this->getHoliday(
                    !empty(${Fields::TITLE}[$k]) ? ${Fields::TITLE}[$k] : null,
                    !empty(${Fields::FROM}[$k]) ? ${Fields::FROM}[$k] : null,
                    !empty(${Fields::TO}[$k]) ? ${Fields::TO}[$k] : null,
                    !empty(${Fields::DESCRIPTION}[$k]) ? ${Fields::DESCRIPTION}[$k] : null,
                    !empty(${Fields::TIMESTAMP}[$k]) ? ${Fields::TIMESTAMP}[$k] : null,
                );
                if(!empty($holiday))
                    $holidays[] = $holiday;
            }
            // dd($holidays);
            return $holidays;
        }
        return null;
    }
    
    protected function getHoliday($title, $from, $to, $description, $timestamp, $carbon_output_format = false){
        
        // dd($timestamp);
        
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
        
        $holiday['timestamp'] = !empty($timestamp) ? $timestamp : null;
        
        return !empty($holiday) ? $holiday : null;
        
    }
    
}