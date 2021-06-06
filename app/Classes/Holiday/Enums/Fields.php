<?php
namespace App\Classes\Holiday\Enums;

use App\Classes\Traits\Enumerable;

// use App\Classes\Holiday\Enums\Fields;

class Fields{
    
    use Enumerable;
    
    const TITLE = "holiday_title";
    const DESCRIPTION = "holiday_description";
    const FROM = "holiday_from";
    const TO = "holiday_to";
    const TIMESTAMP = "holiday_timestamp";
}