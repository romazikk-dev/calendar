<?php
namespace App\Classes\Setting\Settings\CustomFields;

// use App\Classes\Enums\Weekdays;
use App\Classes\Setting\Enums\Keys;
use App\Classes\Language\Enums\Abriviations;
use App\Classes\Setting\Settings\Setting;

class MainCustomFields extends Setting{
    
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
    
}