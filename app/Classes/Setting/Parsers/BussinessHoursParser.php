<?php

namespace App\Classes\Setting\Parsers;

use App\Classes\Setting\Enums\Keys;
use App\Classes\Enums\Weekdays;

class BussinessHoursParser{
    
    public function parse(array $data){
        
        // dd($data);
        
        // $placeholder = \Setting::getPlaceholder(Keys::DEFAULT_BUSINESS_HOURS);
        // dump($placeholder);
        
        $parsed_data = [];
        foreach(Weekdays::all() as $weekday){
            if(isset($data[$weekday]) && !isset($data[$weekday]['is_weekend']) &&
            isset($data[$weekday]['start_hour']) && isset($data[$weekday]['end_hour'])){
                $parsed_data[$weekday] = [
                    "start_hour" => $data[$weekday]['start_hour'],
                    "end_hour" => $data[$weekday]['end_hour']
                ];
            }else{
                $parsed_data[$weekday] = [
                    "is_weekend" => "on"
                ];
            }
        }
        
        return $parsed_data;
        // dd(Weekdays::all());
        
        // dd(2121212);
        
        
        // if(count($data) != 7)
        //     return null;
            
        // $parsed
        // foreach($data){
        // 
        // }
        
    }
    
}