<?php
namespace App\Classes\Setting\Settings\CustomFields;

// use App\Classes\Enums\Weekdays;
use App\Classes\Setting\Enums\Keys;
use App\Classes\Language\Enums\Abriviations;

class CustomFields extends MainCustomFields{
    
    public function getOrPlaceholder($params = []){
        // return 222;
        
        $setting = $this->getSettingFromDB(true);
        
        // dd($setting);
        
        // dd($setting);
        if(empty($setting))
            $setting = $this->getPlaceholder();
        
        // dd($setting);
        
        if(is_string($setting))
            $setting = json_decode($setting, true);
        
        // dd($setting);
        
        $placeholder = $this->getPlaceholder();
        $setting = array_merge($placeholder, $setting);
        
        $setting = $this->sortByLanguagePosition($setting);
        
        // return 222;
        return $setting;
    }
    
    public function sortByLanguagePosition($setting){
        $lang_setting = \Setting::of(Keys::CLIENTS_BOOKING_CALENDAR_LANGUAGES)->getOrPlaceholder();
        if(empty($lang_setting))
            return $setting;
            
        $lang_positions = [];
        foreach($lang_setting as $k => $v){
            // $lang_positions[$v["abr"]] = $v["position"];
            $lang_positions[] = $v["abr"];
        }
        
        // dd($lang_positions);
        
        foreach($setting as $k => &$v){
            if(empty($v["values"]))
                continue;
            
            $sorted_values = [];
            foreach($lang_positions as $kk => $vv){
                if(!empty($v["values"][$vv])){
                    // dd($v["values"][$kk]);
                    $sorted_values[$vv] = $v["values"][$vv];
                }
            }
            
            // $v["values"] = $sorted_values;
            
            $v["values"] = array_merge($sorted_values, $v["values"]);

            // $lang_positions[$v["abr"]] = $v["position"];
        }
        
        return $setting;
        
        // dump($setting);
        // dd($lang_setting);
    }
    
    // public function parseAndSet($data, $return_as_json = false){
    public function parseAndSet($data, $params = []){
        
        
        // if(empty($parsed_data))
        //     return;
        
        // $db_setting = $this->getSettingFromDB(true);
        // $placeholder = $this->getPlaceholder();
        // 
        // if(empty($db_setting)){
        //     $parsed_settings = [];
        //     foreach($placeholder as $k => &$v){
        //         if(!empty($placeholder['values'])){
        //             foreach($placeholder['values'] as $kk => &$vv){
        //                 if(!empty($data['field']['hall'][$vv['abr']]))
        //                     $vv['abr'] = $data['field']['hall'][$vv['abr']];
        //             }
        //         }
        //         // foreach($placeholder as $k => &$v){
        //         // 
        //         // }
        //         // if(!empty($data['field']['hall'][]))
        //     }
        // }
        
        // dump($db_setting);
        // dump($this->getPlaceholder());
        // dump($data);
        // dd($parsed_data);
        
        $parsed_data = $this->parse($data);
        
        // if(!empty($data)){
        //     // $parsed_data = $this->parse($data);
        //     $parsed_data = $this->parse($data);
        // }else{
        //     $parsed_data = $this->getPlaceholder();
        // }
        
        // dd($parsed_data);
        
        $setting_model = $this->setSettingIntoDB($parsed_data);
        // return $return_as_json === true ? $setting_model->data : json_decode($setting_model->data, true);
    }
    
    protected function parse($data){
        $db_setting = $this->getSettingFromDB(true);
        // $placeholder = $this->getPlaceholder();
        
        $parsed_settings = [];
        if(empty($db_setting)){
            $settings_data = $this->getPlaceholder();
        }else{
            $db_setting = json_decode($db_setting, true);
            $settings_data = $db_setting;
        }
        
        $placeholder = $this->getPlaceholder();
        $settings_data = array_merge($placeholder, $settings_data);
        // dd($settings_data);
        
        if(!empty($settings_data[$data['field_name']]['values'])){
            foreach($settings_data[$data['field_name']]['values'] as $k => &$v){
                if(!empty($data['field'][$data['field_name']]) && array_key_exists($v['abr'], $data['field'][$data['field_name']]))
                    $v['value'] = $data['field'][$data['field_name']][$v['abr']];
            }
        }
        
        // dump($data);
        // dd($settings_data);
        
        // if
        
        // foreach($settings_data as $k => &$v){
        //     // dd(11);
        //     if(!empty($v['values'])){
        //         // dd($v);
        //         foreach($v['values'] as $kk => &$vv){
        //             // dd($vv);
        //             if(!empty($data['field'][$v['field']][$vv['abr']])){
        //                 // dd(333);
        //                 $vv['value'] = $data['field'][$v['field']][$vv['abr']];
        //             }
        //         }
        //     }
        // }
        
        return $settings_data;
        // $parsed_settings = $placeholder;
        // 
        // if(empty($db_setting)){
        //     // dd(11);
        //     foreach($placeholder as $k => &$v){
        //         // dd(11);
        //         if(!empty($v['values'])){
        //             // dd($v);
        //             foreach($v['values'] as $kk => &$vv){
        //                 // dd($vv);
        //                 if(!empty($data['field'][$v['field']][$vv['abr']])){
        //                     // dd(333);
        //                     $vv['value'] = $data['field'][$v['field']][$vv['abr']];
        //                 }
        //             }
        //         }
        //     }
        //     $parsed_settings = $placeholder;
        // }else{
        //     // $db_setting = json_decode($db_setting, true);
        //     // dump($data);
        //     // foreach($db_setting as $k => &$v){
        //     //     if(!empty($v['values'])){
        //     //         foreach($db_setting as $k => &$v){
        //     // 
        //     //         }
        //     //     }
        //     //     if(!empty())
        //     // }
        //     // dd($db_setting);
        // }
        // 
        // // dump($db_setting);
        // // dump($this->getPlaceholder());
        // 
        // return $parsed_settings;
    }
    
    public function arrange($data){
        return $data;
        // $data = array_column($data, 'field');
        // 
        // $count_weekends = 0;
        // foreach($data as $k => $v)
        //     if(!empty($v['is_weekend']) && $v['is_weekend'] === "on")
        //         $count_weekends++;
        // 
        // return [
        //     "data" => $data,
        //     "count_weekends" => $count_weekends,
        //     "count_workdays" => 7 - $count_weekends
        // ];
    }
    
    public function getPlaceholderKeys(){
        $placeholder = $this->getPlaceholder();
        if(!empty($placeholder))
            return array_keys($placeholder);
        return null;
    }
    
    public function getPlaceholder($params = []){
        $available_languages = \Language::getBookingCalendarAvailableLanguages();
        
        // dd($available_languages);
        // dd($this->custom_fields);
        $placeholder = [];
        foreach($this->custom_fields as $custom_field){
            $item = [
                'field' => $custom_field['key'],
                'title' => $custom_field['title'],
                'values' => []
            ];
            foreach($available_languages as $lang){
                $item['values'][$lang['abr']] = [
                    'abr' => $lang['abr'],
                    'title' => $lang['title'],
                    'value' => null
                ];
            }
            $placeholder[$custom_field['key']] = $item;
        }
        
        // dd($placeholder);
        
        return $placeholder;
    }
    
}