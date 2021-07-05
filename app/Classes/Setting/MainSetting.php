<?php

namespace App\Classes\Setting;

// use App\Classes\Setting\Enums\Keys;
use App\Classes\Setting\Arrangers\Arranger;
use App\Classes\Setting\Placeholders\Placeholder;
use App\Classes\Setting\Parsers\Parser;
use App\Classes\Setting\Nav;
use App\Models\Setting;
use App\Classes\Setting\Enums\Keys;
use Auth;

class MainSetting{
    
    // protected $placeholder;
    // protected $arranger;
    // protected $parser;
    protected $nav;
    
    protected $settings = [];
    
    protected $aliases = [
        //Worker
        Keys::WORKER_DEFAULT_BUSINESS_HOURS => \App\Classes\Setting\Settings\BussinessHours::class,
        
        //Hall
        Keys::HALL_DEFAULT_BUSINESS_HOURS => \App\Classes\Setting\Settings\BussinessHours::class,
        Keys::HALL_DEFAULT_TIMEZONE => \App\Classes\Setting\Settings\Timezone::class,
        
        //Client`s booking calendar
        Keys::CLIENTS_BOOKING_CALENDAR_MAIN => \App\Classes\Setting\Settings\Main::class,
        Keys::CLIENTS_BOOKING_CALENDAR_LANGUAGES => \App\Classes\Setting\Settings\LanguagePicker::class,
        Keys::CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES => \App\Classes\Setting\Settings\CustomFields\CustomFields::class,
        Keys::ADMINS_BOOKING_CALENDAR_MAIN => \App\Classes\Setting\Settings\AdminsBookingCalendar\Main::class,
    ];
    
    function __construct() {
        // $this->placeholder = new Placeholder();
        // $this->parser = new Parser();
        $this->nav = new Nav();
        // $this->arranger = new Arranger();
    }
    
    public function initSettingClass($key, $params = []){
        if(!array_key_exists($key, $this->aliases))
            return false;
            
        if(!array_key_exists($key, $this->settings)){
            $class_name = $this->aliases[$key];
            $this->settings[$key] = new $class_name($key);
        }
        return true;
    }
    
    // protected function getSettingFromDB($key, $only_data = false){
    //     $setting = Setting::byKey($key)->first();
    //     if(empty($setting) || is_null($setting->data) || $setting->data == "null"){
    //         if(!empty($setting))
    //             Setting::where('key', $key)->delete();
    //         return null;
    //     }
    //     return $only_data === true ? $setting->data : $setting;
    // }
    
    // protected function setSettingIntoDB($key, $parsed_data){
    //     // dd($key);
    //     $setting = $this->getSettingFromDB($key);
    //     // dd($setting_model);
    //     if(empty($setting)){
    //         $setting = Setting::create([
    //             'user_id' => Auth::user()->id,
    //             'key' => $key,
    //             'data' => json_encode($parsed_data)
    //         ]);
    //     }else{
    //         $setting->data = json_encode($parsed_data);
    //         $setting->save();
    //     }
    //     return $setting;
    // }
    
    // protected function getPlaceholderPerKey($key, $params = []){
    //     return $this->placeholder->get($key, $params);
    // }
    
    // protected function arrangePerKey($key, $setting){
    //     return $this->arranger->arrange($key, $setting);
    // }
    
    public function getNav($key = false){
        return $this->nav->get($key);
    }
    
}