<?php

namespace App\Classes\Setting;

use App\Classes\Setting\Enums\Keys;

class Nav{
    
    private $items;
    
    function __construct(){
        $this->setAllItems();
    }
    
    public function get($key = false){
        return !empty($key) && array_key_exists($key, $this->items) ? $this->items[$key] : $this->items;
    }
    
    private function setAllItems(){
        $this->items = [
            Keys::DEFAULT_BUSINESS_HOURS => [
                'title' => 'Hall`s default bussiness hours',
                'route' => route('dashboard.settings.hall.default_business_hours'),
                'route_name' => 'dashboard.settings.hall.default_business_hours',
            ],
            Keys::WORKER_DEFAULT_BUSINESS_HOURS => [
                'title' => 'Worker`s default bussiness hours',
                'route' => route('dashboard.settings.worker.default_business_hours'),
                'route_name' => 'dashboard.settings.worker.default_business_hours',
            ],
            Keys::LANGUAGES => [
                'title' => 'Languages',
                'route' => route('dashboard.settings.hall.default_business_hours'),
                'route_name' => 'dashboard.settings.hall.default_business_hours',
            ],
            Keys::CUSTOM_TITLES => [
                'title' => 'Custom titles',
                'route' => route('dashboard.settings.hall.default_business_hours'),
                'route_name' => 'dashboard.settings.hall.default_business_hours',
            ],
        ];
    }
    
}