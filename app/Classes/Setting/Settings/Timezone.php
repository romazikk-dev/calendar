<?php

namespace App\Classes\Setting\Settings;

use App\Classes\Enums\Weekdays;
use App\Classes\Setting\Enums\Keys;

class Timezone extends Setting{
    
    public function getOrPlaceholder($params = []){
        if(!empty($params['user_id']) && is_numeric($params['user_id']))
        $setting = $this->getSettingFromDB(
            true,
            !empty($params['user_id']) && is_numeric($params['user_id']) ? $params['user_id'] : null
        );
        
        // dd($setting);
        if(empty($setting))
            $setting = $this->getPlaceholder();
            
        if(is_string($setting))
            $setting = json_decode($setting, true);
        
        // if(array_key_exists('arrange', $params) && $params['arrange'] === true)
        //     $setting = $this->arrange($setting);
        // dd(1111);
        // dd($setting);
        // if(array_key_exists('as_json', $params) && $params['as_json'] === true)
        // return $as_json === true ? json_decode($setting, true) : $setting;
        return $setting;
        // if($as_json)
        //     return $setting;
        // return json_decode($setting, true);
    }
    
    public function parseAndSet($data, $params = []){
        $parsed_data = $this->parse($data);
        $setting_model = $this->setSettingIntoDB($parsed_data);
    }
    
    protected function parse(array $data){
        $timezones = \Timezone::getArrWithKeyGroup();
        return [
    		"region" => $data["timezone_region"],
    		"key" => $data["timezone_key"],
    		"timezone" => $timezones[$data["timezone_region"]][$data["timezone_key"]]['timezone'],
        ];
    }
    
    // public function arrange($data){
    //     $count_weekends = 0;
    //     foreach($data as $k => $v)
    //         if(!empty($v['is_weekend']) && $v['is_weekend'] === "on")
    //             $count_weekends++;
    // 
    //     return [
    //         "data" => $data,
    //         "count_weekends" => $count_weekends,
    //         "count_workdays" => 7 - $count_weekends
    //     ];
    // }
    
    public function getPlaceholder(){
        return [
    		"region" => "utc",
    		"key" => "utc",
    		"timezone" => "UTC",
        ];
    }
    
}