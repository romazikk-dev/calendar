<?php

namespace App\Classes\Setting;

// use App\Classes\Setting\Enums\Keys;
use App\Classes\Setting\Placeholders\Placeholder;
use App\Classes\Setting\Parsers\Parser;
use App\Classes\Setting\Nav;
use App\Models\Setting;
use App\Classes\Setting\Enums\Keys;
use Auth;

class MainSetting{
    
    protected $placeholder;
    protected $parser;
    protected $nav;
    
    function __construct() {
        $this->placeholder = new Placeholder();
        $this->parser = new Parser();
        $this->nav = new Nav();
    }
    
    protected function getSettingFromDB($key, $only_data = false){
        $setting = Setting::byKey($key)->first();
        if(empty($setting) || is_null($setting->data) || $setting->data == "null"){
            if(!empty($setting))
                Setting::where('key', $key)->delete();
            return null;
        }
        // if($only_data)
        return (is_bool($only_data) && $only_data) ? $setting['data'] : $setting;
    }
    
    protected function setSettingIntoDB($key, $parsed_data){
        // dd($key);
        $setting = $this->getSettingFromDB($key);
        // dd($setting_model);
        if(empty($setting)){
            $setting = Setting::create([
                'user_id' => Auth::user()->id,
                'key' => Keys::DEFAULT_BUSINESS_HOURS,
                'data' => json_encode($parsed_data)
            ]);
        }else{
            $setting->data = json_encode($parsed_data);
            $setting->save();
        }
        return $setting;
    }
    
    protected function getPlaceholderPerKey($key){
        return $this->placeholder->get($key);
    }
    
    public function getNav($key = false){
        return $this->nav->get($key);
    }
    
}