<?php
namespace App\Classes\Setting\Settings\AdminsBookingCalendar\Enums;

use App\Classes\Traits\Enumerable;

class MainKeys{
    
    use Enumerable;
    
    const MONTH_MAX_EVENTS_PER_DAY_TO_SHOW = "month_max_events_per_day_to_show";
    const WEEK_MAX_EVENTS_PER_DAY_TO_SHOW = "week_max_events_per_day_to_show";
    const DAY_MAX_EVENTS_PER_DAY_TO_SHOW = "day_max_events_per_day_to_show";
    const LIST_MAX_EVENTS_PER_DAY_TO_SHOW = "list_max_events_per_day_to_show";
    const ENABLE_BOOKING_ON_ANY_TIME = "enable_booking_on_any_time";
}