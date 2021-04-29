<?php

namespace App\Classes\Setting;

use App\Classes\Setting\Enums\Keys;

// use App\Classes\Setting\Enums\Keys;
// use Illuminate\Http\Request;
// use App\Models\Booking;
// use App\Models\User;
// use App\Models\Setting as SettingModel;
// use App\Models\Template;
// use App\Models\Suspension;
// use App\Exceptions\Api\Calendar\BadRangeException;
// use App\Classes\Range\Range;

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
            ],
            'custom' => [
                'title' => 'Custom asd as da sdasd as dasda',
                'route' => route('dashboard.settings.hall.default_business_hours'),
            ],
        ];
    }
    
}