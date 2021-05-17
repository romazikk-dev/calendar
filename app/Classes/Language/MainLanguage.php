<?php

namespace App\Classes\Language;

use App\Classes\Language\Enums\Abriviations;
use App\Models\Setting as Setting;

class MainLanguage{
    
    protected $validation_rules = [
        'lang' => 'array',
        'lang.*' => 'in:on'
    ];
    
    protected $booking_calendar_available_languages = [
        [
            'abr' => Abriviations::EN,
            'title' => 'english'
        ],
        [
            'abr' => Abriviations::DE,
            'title' => 'german'
        ],
        [
            'abr' => Abriviations::FR,
            'title' => 'french'
        ],
        // [
        //     'abr' => Abriviations::RU,
        //     'title' => 'russian'
        // ],
        // [
        //     'abr' => Abriviations::UA,
        //     'title' => 'ukrainian'
        // ],
    ];
    
}