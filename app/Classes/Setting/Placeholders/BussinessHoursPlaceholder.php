<?php

namespace App\Classes\Setting\Placeholders;

use App\Classes\Enums\Weekdays;

class BussinessHoursPlaceholder{
    
    public function get(){
        return [
            Weekdays::MONDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => false,
        	],
            Weekdays::TUESDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => false,
        	],
            Weekdays::WEDNESDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => false,
        	],
            Weekdays::THURSDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => false,
        	],
            Weekdays::FRIDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => false,
        	],
            Weekdays::SATURDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => true,
        	],
            Weekdays::SUNDAY => [
        		"start_hour" => "09:00",
        		"end_hour" => "19:00",
        		"is_weekend" => true,
        	],
        ];
    }
    
}