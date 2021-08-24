<?php
namespace App\Classes\Timezone;

use App\Classes\Setting\Enums\Keys as SettingKeys;

class Timezone extends MainTimezone{
    
    public function getTimezoneValuesForVueAppInRegardToRequest(){
        $setting = \Setting::of(SettingKeys::HALL_DEFAULT_TIMEZONE);
        
        $values = [];
        if(old('_token')){
            if(old('timezone_region'))
                $values['timezone_region'] = old('timezone_region');
            if(old('timezone_key'))
                $values['timezone_key'] = old('timezone_key');
        }else{
            $settings = $setting->getOrPlaceholder();
            $values['timezone_region'] = $settings['region'];
            $values['timezone_key'] = $settings['key'];
        }
        
        return $values;
    }
    
    public function getCurrentTimezone($user_id = null){
        if(empty($user_id) || !is_numeric($user_id)){
            $auth_user = auth()->user();
            $user_id = !empty($auth_user) ? $auth_user->id : null;
        }
        
        $setting = \Setting::of(SettingKeys::HALL_DEFAULT_TIMEZONE);
        if(empty($user_id))
            return $setting->getPlaceholder()['timezone'];
            
        return $setting->getOrPlaceholder([
            'user_id' => $user_id
        ])['timezone'];
    }
    
    public function getValuesInRegardToRequest(){
        if(old('_token')){
            dd(old());
        }else{
            
        }
    }
    
    public function getValidationRules($request){
        $timezones = $this->getArrWithKeyGroup();
        $timezone_groups = array_keys($timezones);
        
        // dd($request->has('timezone_region'));
        
        $validation_rules = [
            'timezone_region' => 'required|string|in:' . implode(',', $timezone_groups),
        ];
        
        $timezone_keys = [];
        if($request->has('timezone_region') && !empty($request->timezone_region) && in_array($request->timezone_region, $timezone_groups)){
            $keys = array_keys($timezones[$request->timezone_region]);
            $timezone_keys = array_unique(array_merge($timezone_keys, $keys));
        }else{
            foreach($timezones as $k => $v){
                $v_keys = array_keys($v);
                $timezone_keys = array_unique(array_merge($timezone_keys, $v_keys));
            }
        }
        
        $validation_rules['timezone_key'] = 'required|string|in:' . implode(',', $timezone_keys);
        
        // dd($validation_rules);
        
        return $validation_rules;
    }
    
    public function getArrWithKeyGroup(){
        $timezones_parsed = [];
        $timezones_arr = timezone_identifiers_list();
        
        foreach($timezones_arr as $timezone){
            $timezone_arr = explode('/', $timezone);
            $timezone_arr = array_map(function($v){
                return strtolower($v);
            }, $timezone_arr);
            
            $group = $timezone_arr[0];
            if(!array_key_exists($group, $timezones_parsed))
                $timezones_parsed[$group] = [];
            
            $key = implode('_', $timezone_arr);
            $timezone_item = [
                "key" => $key,
                "timezone" => $timezone,
            ];
            $timezones_parsed[$group][$key] = $timezone_item;
        }
        
        return $timezones_parsed;
    }
    
}