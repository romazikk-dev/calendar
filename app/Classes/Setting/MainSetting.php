<?php

namespace App\Classes\Setting;

// use App\Classes\Setting\Enums\Keys;
use App\Classes\Setting\Placeholders\Placeholder;

class MainSetting{
    
    private $placeholder;
    
    function __construct() {
        $this->placeholder = new Placeholder();
    }
    
    protected function getPlaceholderPerKey($key){
        return $this->placeholder->get($key);
    }
    
    public function getNavs(){
        return [
            'default_bussiness_hours' => [
                'title' => 'Default bussiness hours',
                'route' => route('dashboard.settings.bussiness_hours'),
            ],
        ];
    }
    
}