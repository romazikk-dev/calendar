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

class Setting extends MainSetting{
    
    public function getOrPlaceholder($key, $as_json = false){
        $setting = $this->getSettingFromDB($key, true);
        if(empty($setting))
            return $this->getPlaceholderPerKey($key);
        if($as_json)
            return $setting;
        return json_decode($setting, true);
    }
    
    public function getOrNull($key, $as_json = false){
        $setting = SettingModel::byKey($key)->first();
        if(empty($setting))
            return null;
    }
    
    public function getPlaceholder($key, $as_json = false){
        $placeholder = $this->getPlaceholderPerKey($key);
        if(empty($placeholder))
            return null;
        return is_bool($as_json) && $as_json ? json_encode($placeholder) : $placeholder;
    }
    
    public function parseAndSet($key, $data, $return_as_json = false){
        $parsed_data = $this->parser->parse($key, $data);
        $setting_model = $this->setSettingIntoDB($key, $parsed_data);
        return $return_as_json === true ? $setting_model->data : json_decode($setting_model->data, true);
    }
    
}