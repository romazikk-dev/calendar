<?php

namespace App\Classes\Setting\Settings;

// use App\Classes\Enums\Weekdays;
use App\Classes\Setting\Enums\Keys;
use App\Classes\Language\Enums\Abriviations;

class LanguagePicker extends Setting{
    
    public function getOrPlaceholder($params = []){
        $setting = $this->getSettingFromDB(true);
        
        // dd($setting);
        if(empty($setting))
            $setting = $this->getPlaceholder();
            
        if(is_string($setting))
            $setting = json_decode($setting, true);
        
        usort($setting, function($a, $b){
            return $a['position'] > $b['position'];
        });
        
        return $setting;
    }
    
    // public function parseAndSet($data, $return_as_json = false){
    public function parseAndSet($data, $params = []){
        
        // dd($data);
        
        if(!empty($data)){
            $parsed_data = $this->parse($data);
        }else{
            $parsed_data = $this->getPlaceholder([], true);
        }
        // dd($parsed_data);
        $setting_model = $this->setSettingIntoDB($parsed_data);
        // return $return_as_json === true ? $setting_model->data : json_decode($setting_model->data, true);
    }
    
    protected function parse($data){
        $setting = $this->getSettingFromDB(true);
        if(is_string($setting))
            $setting = json_decode($setting, true);
        
        if(empty($setting))
            $setting = $this->getPlaceholder();
            
        // dd($setting);
        
        foreach($setting as $k => &$v){
            $on_found = false;
            foreach($data["lang"] as $kk => $vv){
                if(!empty($data["position"]) && array_key_exists($v["abr"], $data["position"])){
                    $v["position"] = (int) $data["position"][$v["abr"]];
                }
                if($kk == $v["abr"]){
                    $v["on"] = 1;
                    $on_found = true;
                }
            }
            if(!$on_found)
                $v["on"] = 0;
        }
        
        return $setting;
    }
    
    public function getPlaceholder($params = [], $for_set = false){
        $available_languages = \Language::getBookingCalendarAvailableLanguages();
        
        // dd($available_languages);
        
        foreach($available_languages as &$lang){
            if(!empty($params)){
                if(!empty($params['status_'.$lang['abr']]) && $params['status_'.$lang['abr']] === true){
                    $lang['on'] = 1;
                }else{
                    $lang['on'] = 0;
                }
            }else{
                if(!$for_set){
                    // $lang['on'] = 0;
                    if($lang['abr'] == Abriviations::EN){
                        $lang['on'] = 1;
                    }else{
                        $lang['on'] = 0;
                    }
                }else{
                    $lang['on'] = 0;
                }
            }
        }
        
        return $available_languages;
    }
    
}