<?php
namespace App\Classes\Setting\Enums;

use App\Classes\Traits\Enumerable;

// use App\Classes\Setting\Enums\Keys;

class Keys{
    
    use Enumerable;
    
    /**
    *   Default Hall business hours
    */
    const HALL_DEFAULT_BUSINESS_HOURS = "hall_default_bussiness_hours";
    
    /**
    *   Default Worker business hours
    */
    const HALL_HOLIDAYS_FOR_ALL = "hall_holidays_for_all";
    
    /**
    *   Main hall`s setting
    */
    const HALL_DEFAULT_TIMEZONE = "hall_default_timezone";
    
    /**
    *   Default Worker business hours
    */
    const WORKER_DEFAULT_BUSINESS_HOURS = "worker_default_bussiness_hours";
    
    /**
    *   Main settings of client booking calendar
    */
    const CLIENTS_BOOKING_CALENDAR_MAIN = "clients_booking_calendar_main";
    
    /**
    *   Languages of client booking calendar to use
    */
    const CLIENTS_BOOKING_CALENDAR_LANGUAGES = "clients_booking_calendar_languages";
    
    /**
    *   Custom titles of client booking calendar
    */
    const CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES = "clients_booking_calendar_custom_titles";
    
    /**
    *   Main settings of admin booking calendar
    */
    const ADMINS_BOOKING_CALENDAR_MAIN = "admins_booking_calendar_main";
    
    /**
    *   Main settings of template
    */
    const TEMPLATE_MAIN = "template_main";
    
    /**
    *   Specifics of template (for whom, what kind of service provides)
    */
    const TEMPLATE_SPECIFICS = "template_specifics";
    
}