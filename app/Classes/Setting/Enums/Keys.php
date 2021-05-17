<?php
namespace App\Classes\Setting\Enums;

use App\Classes\Traits\Enumerable;

class Keys{
    
    use Enumerable;
    
    /**
    *   Setting key
    *   Default Hall business hours
    */
    const HALL_DEFAULT_BUSINESS_HOURS = "hall_default_bussiness_hours";
    
    /**
    *   Setting key
    *   Default Worker business hours
    */
    const HALL_HOLIDAYS_FOR_ALL = "hall_holidays_for_all";
    
    /**
    *   Setting key
    *   Default Worker business hours
    */
    const WORKER_DEFAULT_BUSINESS_HOURS = "worker_default_bussiness_hours";
    
    /**
    *   Setting key
    *   Languages of client booking calendar to use
    */
    const CLIENTS_BOOKING_CALENDAR_LANGUAGES = "clients_booking_calendar_languages";
    
    /**
    *   Setting key
    *   Custom titles of client booking calendar
    */
    const CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES = "clients_booking_calendar_custom_titles";
    
    /**
    *   Setting key
    *   Params of client`s booking calendar
    */
    // const CLIENTS_BOOKING_CALENDAR_PARAMS = "clients_booking_calendar_params";
    // const HOLIDAYS_FOR_ALL_HALLS = "holidays_for_all_halls";
}