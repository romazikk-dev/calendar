<?php

namespace App\Classes\Language;

// use App\Classes\Setting\Enums\Keys;
use App\Models\Setting as Setting;

class Language extends MainLanguage{
    
    function getBookingCalendarAvailableLanguages(){
        return $this->booking_calendar_available_languages;
    }
    
    function getValidationRules(){
        return $this->validation_rules;
    }
    
}