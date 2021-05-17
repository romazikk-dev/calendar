<?php

namespace App\Classes\Setting;

use App\Classes\Setting\Enums\Keys;
// use Illuminate\Http\Request;
// use App\Models\Booking;
// use App\Models\User;
use App\Models\Setting as SettingModel;
// use App\Models\Template;
// use App\Models\Suspension;
// use App\Exceptions\Api\Calendar\BadRangeException;
// use App\Classes\Range\Range;

// Setting::arrangeByKey($key, $setting)

class Setting extends MainSetting{
    
    public function of($key){
        // $setting = $this->getSettingFromDB($key, true);
        if(!$this->initSettingClass($key))
            return null;
        return $this->settings[$key];
    }
    
    // public function getOrPlaceholder($key, $params = []){
    //     if(!$this->initSettingClass($key))
    //         return null;
    // 
    //     $setting = $this->getSettingFromDB($key, true);
    // 
    //     // dd($setting);
    //     if(empty($setting))
    //         $setting = $this->settings[$key]->getPlaceholder();
    // 
    //     if(is_string($setting))
    //         $setting = json_decode($setting, true);
    // 
    //     if(array_key_exists('arrange', $params))
    //         $setting = $this->arrangeByKey($key, $setting);
    //     // dd(1111);
    //     // dd($setting);
    //     return $as_json === true ? json_decode($setting, true) : $setting;
    //     // if($as_json)
    //     //     return $setting;
    //     // return json_decode($setting, true);
    // }
    
    // public function getOrPlaceholder($key, $arrange = false, $as_json = false){
    //     $setting = $this->getSettingFromDB($key, true);
    //     // dd($setting);
    //     // dd(json_decode($setting, true));
    //     // dd($this->getPlaceholderPerKey($key));
    //     if(empty($setting))
    //         $setting = $this->getPlaceholderPerKey($key);
    //     if(is_string($setting))
    //         $setting = json_decode($setting, true);
    //     if($arrange)
    //         $setting = $this->arrangeByKey($key, $setting);
    //     // dd(1111);
    //     // dd($setting);
    //     return $as_json === true ? json_decode($setting, true) : $setting;
    //     // if($as_json)
    //     //     return $setting;
    //     // return json_decode($setting, true);
    // }
    
    // public function arrangeByKey($key, $setting){
    //     return $this->arranger->arrange($key, $setting);
    //     // arrangePerKey
    // }
    
    public function getOrNull($key, $as_json = false){
        $setting = SettingModel::byKey($key)->first();
        if(empty($setting))
            return null;
    }
    
    public function getPlaceholder($key, $as_json = false, $params = []){
        // dd($params);
        $placeholder = $this->getPlaceholderPerKey($key, $params);
        if(empty($placeholder))
            return null;
        return is_bool($as_json) && $as_json ? json_encode($placeholder) : $placeholder;
    }
    
    public function parseAndSet($key, $data, $return_as_json = false){
        $parsed_data = $this->parser->parse($key, $data);
        // dd($parsed_data);
        $setting_model = $this->setSettingIntoDB($key, $parsed_data);
        return $return_as_json === true ? $setting_model->data : json_decode($setting_model->data, true);
    }
    
    // public function set($key, $data, $return_as_json = false){
    //     // $parsed_data = $this->parser->parse($key, $data);
    //     $setting_model = $this->setSettingIntoDB($key, $data);
    //     return $return_as_json === true ? $setting_model->data : json_decode($setting_model->data, true);
    // }
    
}