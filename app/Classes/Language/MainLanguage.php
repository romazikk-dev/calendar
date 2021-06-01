<?php

namespace App\Classes\Language;

use App\Classes\Language\Enums\Abriviations;
use App\Models\Setting as Setting;

class MainLanguage{
    
    protected $validation_rules = [
        'lang' => 'array',
        'lang.*' => 'in:on',
        'position' => 'nullable|array',
        'position.*' => 'nullable|integer',
    ];
    
    protected $booking_calendar_available_languages = [
        [
            'abr' => Abriviations::EN,
            'title' => 'english',
            'position' => 1,
        ],
        [
            'abr' => Abriviations::DE,
            'title' => 'german',
            'position' => 2,
        ],
        [
            'abr' => Abriviations::FR,
            'title' => 'french',
            'position' => 3,
        ],
    ];
    
}