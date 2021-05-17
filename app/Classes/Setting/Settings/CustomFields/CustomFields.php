<?php
namespace App\Classes\Setting\Settings\CustomFields;

// use App\Classes\Enums\Weekdays;
use App\Classes\Setting\Enums\Keys;
use App\Classes\Language\Enums\Abriviations;

class CustomFields extends MainCustomFields{
    
    protected $custom_fields_list = [
        'hall' => [
            'key' => 'hall',
            'title' => 'Hall',
        ],
        'template' => [
            'key' => 'template',
            'title' => 'Template',
        ]
    ];
    
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
        
        // return 222;
        return $setting;
    }
    
    // public function parseAndSet($data, $return_as_json = false){
    // public function parseAndSet($data, $params = []){
    //     if(!empty($data)){
    //         $parsed_data = $this->parse($data);
    //     }else{
    //         $parsed_data = $this->getPlaceholder([], true);
    //     }
    //     // dd($parsed_data);
    //     $setting_model = $this->setSettingIntoDB($parsed_data);
    //     // return $return_as_json === true ? $setting_model->data : json_decode($setting_model->data, true);
    // }
    
    // protected function parse($data){
    //     $params = [];
    //     if(!empty($data))
    //         foreach($data as $k => $v)
    //             $params['status_' . $k] = true;
    // 
    //     // dump($params);
    // 
    //     $parsed_data = $this->getPlaceholder($params);
    // 
    //     // dd($parsed_data);
    // 
    //     return $parsed_data;
    // }
    
    public function getPlaceholder($params = [], $for_set = false){
        $available_languages = \Language::getBookingCalendarAvailableLanguages();
        
        // dd($available_languages);
        $placeholder = [];
        foreach($this->custom_fields_list as $custom_field){
            $item = [
                'field' => $custom_field,
                'values' => []
            ];
            foreach($available_languages as $lang){
                $item['values'][$lang['abr']] = null;
            }
            $placeholder[$custom_field] = $item;
        }
        
        // dd($placeholder);
        
        return $placeholder;
    }
    
}