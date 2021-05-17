<?php

namespace App\Classes\Setting\Settings;

use App\Classes\Enums\Weekdays;
use App\Classes\Setting\Enums\Keys;

class BussinessHours extends Setting{
    
    public function getOrPlaceholder($params = []){
        $setting = $this->getSettingFromDB(true);
        
        // dd($setting);
        if(empty($setting))
            $setting = $this->getPlaceholder();
            
        if(is_string($setting))
            $setting = json_decode($setting, true);
        
        if(array_key_exists('arrange', $params) && $params['arrange'] === true)
            $setting = $this->arrange($setting);
        // dd(1111);
        // dd($setting);
        // if(array_key_exists('as_json', $params) && $params['as_json'] === true)
        // return $as_json === true ? json_decode($setting, true) : $setting;
        return $setting;
        // if($as_json)
        //     return $setting;
        // return json_decode($setting, true);
    }
    
    // public function parseAndSet($data, $return_as_json = false){
    public function parseAndSet($data, $params = []){
        $parsed_data = $this->parse($data);
        // dd($parsed_data);
        $setting_model = $this->setSettingIntoDB($parsed_data);
        // return $return_as_json === true ? $setting_model->data : json_decode($setting_model->data, true);
    }
    
    protected function parse(array $data){        
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
    }
    
    protected function arrange($data){
        $count_weekends = 0;
        foreach($data as $k => $v)
            if(!empty($v['is_weekend']) && $v['is_weekend'] === "on")
                $count_weekends++;
        
        return [
            "data" => $data,
            "count_weekends" => $count_weekends,
            "count_workdays" => 7 - $count_weekends
        ];
    }
    
    public function getPlaceholder(){
        return [
            Weekdays::MONDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => false,
        	],
            Weekdays::TUESDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => false,
        	],
            Weekdays::WEDNESDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => false,
        	],
            Weekdays::THURSDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => false,
        	],
            Weekdays::FRIDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => false,
        	],
            Weekdays::SATURDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => true,
        	],
            Weekdays::SUNDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => true,
        	],
        ];
    }
    
}