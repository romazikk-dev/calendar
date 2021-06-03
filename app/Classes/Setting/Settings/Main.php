<?php

namespace App\Classes\Setting\Settings;

// use App\Classes\Enums\Weekdays;
use App\Classes\Setting\Enums\Keys;
use App\Classes\Language\Enums\Abriviations;

class Main extends Setting{
    
    public function getOrPlaceholder($params = []){
        $setting = $this->getSettingFromDB(
            true,
            array_key_exists("user_id", $params) && is_numeric($params["user_id"]) ? (int)$params["user_id"] : null
        );
        
        // dd(111);
        // dd($setting);
        if(empty($setting))
            $setting = $this->getPlaceholder();
            
        if(is_string($setting))
            $setting = json_decode($setting, true);
        
        return $setting;
    }
    
    // public function parseAndSet($data, $return_as_json = false){
    public function parseAndSet($data){
        
        $parsed_data = $this->parse($data);
        $setting_model = $this->setSettingIntoDB($parsed_data);
    }
    
    protected function parse($data){
        $setting = $this->getSettingFromDB(true);
        
        if(is_string($setting))
            $setting = json_decode($setting, true);
        
        $placeholder = $this->getPlaceholder();
        if(empty($setting)){
            $setting = $placeholder;
        }else{
            $setting = array_merge($placeholder, $setting);
        }
        
        // dd($data);
        // dd($setting);
        // $placeholder = $this->getPlaceholder();
        $setting_keys = array_keys($setting);
        foreach($setting_keys as $key){
            if(array_key_exists($key, $data)){
                $value = $data[$key];
                if(is_numeric($value))
                    $value = (int) $value;
                $setting[$key] = $value;
            }
        }
        
        // dd($setting);
        
        return $setting;
    }
    
    public function getPlaceholder(){
        return [
            // 'max_future_booking_offset' => (60 * 60 * 24) * 30,
            'max_future_booking_offset' => 30,
        ];
    }
    
}