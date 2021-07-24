<?php

namespace App\Classes\Setting\Settings\Template;

// use App\Classes\Enums\Weekdays;
use App\Classes\Setting\Enums\Keys;
use App\Classes\Language\Enums\Abriviations;
use App\Classes\Setting\Settings\Setting;

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
        
        // dd($setting);
            
        if(is_string($setting))
            $setting = json_decode($setting, true);
            
        $setting = array_unique(array_merge($this->getPlaceholder(), $setting));
        
        return $setting;
    }
    
    // public function parseAndSet($data, $return_as_json = false){
    public function parseAndSet($data){
        $parsed_data = $this->parse($data);
        $setting_model = $this->setSettingIntoDB($parsed_data);
    }
    
    protected function parse($data){
        $setting = $this->getSettingFromDB(true);
        
        // dd($setting);
        
        if(is_string($setting))
            $setting = json_decode($setting, true);
        
        $placeholder = $this->getPlaceholder();
        // dd($placeholder);
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
            if($key == 'duration_range' && !empty($data['duration_range_start']) && !empty($data['duration_range_end'])){
                $setting['duration_range'] = [
                    'start' => (int)$data['duration_range_start'],
                    'end' => (int)$data['duration_range_end']
                ];
            }
            // if(array_key_exists($key, $data)){
                // if()
                // $value = $data[$key];
                // if(is_numeric($value))
                //     $value = (int) $value;
                // $setting[$key] = $value;
            // }
        }
        
        // dd($data);
        // dd($setting);
        
        return $setting;
    }
    
    public function getPlaceholder(){
        return [
            'duration_range' => [
                'start' => 10,
                'end' => 180,
            ],
        ];
    }
    
}