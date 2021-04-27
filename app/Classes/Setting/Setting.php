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
        $setting = SettingModel::byKey($key)->first();
        if(empty($setting)){
            // dd(1111);
            $placeholder = $this->getPlaceholderPerKey($key);
            // dd(1111);
            return $placeholder;
        }
    }
    
    public function getOrNull($key, $as_json = false){
        $setting = SettingModel::byKey($key)->first();
        if(empty($setting))
            return null;
    }
    
    // public function getPlaceholder($key, $as_json = false){
    //     $default = $this->getPlaceholder($key);
    //     if(!empty($default))
    //         return $default;
    //     return null;
    // }
    
    // public function hasPlaceholder($key){
    //     return !empty($this->getDefault($key));
    // }
    
}