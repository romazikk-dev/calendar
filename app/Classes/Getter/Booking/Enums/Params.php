<?php
namespace App\Classes\Getter\Booking\Enums;

use App\Classes\Traits\Enumerable;

// use App\Classes\Getter\Booking\Enums\Params;

class Params{
    
    use Enumerable;
    
    const OWNER = "owner";
    const ID = "id";
    const HALL = "hall";
    const WORKER = "worker";
    const TEMPLATE = "template";
    const CLIENT = "client";
    const WITH = "with";
    const STATUS = "status";
    
    const DURATION_START = "duration_start";
    const DURATION_END = "duration_end";
    
    const HALL_MODEL = "hall_model";
    const WORKER_MODEL = "worker_model";
    const TEMPLATE_MODEL = "template_model";
    const CLIENT_MODEL = "client_model";
    
    const PAST_IGNORE = "past_ignore";
    const ONLY_APPROVED = "only_approved";
    const RESTRICT_TO_MAX_DATE = "restrict_to_max_date";
    const EXCLUDE_IDS = "exclude_ids";
    const FIRST_ITEMS = "first_items";
    
    const EXCLUDE_RANGE_START_AND_END_DATES = "exclude_range_start_and_end_dates";
    // Includes events that don`t have start date in range
    // but have duration that crosses a range
    const ALL_IN_RANGE = "all_in_range";
    
    // client id
    const WITH_EVENTS_PER_CLIENT = "with_events_per_client";
}