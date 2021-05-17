<?php

namespace App\Classes\Setting\Settings;

// use App\Classes\Setting\Settings\Setting;

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

class Setting{
    protected $setting_key = null;
    
    function __construct($setting_key) {
        $this->setting_key = $setting_key;
    }
    
    protected function isInDB(){
        return SettingModel::byKey($this->setting_key)->exists();
    }
    
    protected function getSettingFromDB($only_data = false){
        $setting = SettingModel::byKey($this->setting_key)->first();
        if(empty($setting) || is_null($setting->data) || $setting->data == "null"){
            if(!empty($setting))
                SettingModel::where('key', $this->setting_key)->delete();
            return null;
        }
        return $only_data === true ? $setting->data : $setting;
    }
    
    protected function setSettingIntoDB($parsed_data){
        $setting = $this->getSettingFromDB($this->setting_key);
        if(empty($setting)){
            $setting = SettingModel::create([
                'user_id' => \Auth::user()->id,
                'key' => $this->setting_key,
                'data' => json_encode($parsed_data)
            ]);
        }else{
            $setting->data = json_encode($parsed_data);
            $setting->save();
        }
        return $setting;
    }
}