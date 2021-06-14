<?php
namespace App\Classes\Getter\Booking\Enums;

use App\Classes\Traits\Enumerable;

// use App\Classes\Getter\Booking\Enums\Params;

class Params{
    
    use Enumerable;
    
    const ID = "id";
    const HALL = "hall";
    const WORKER = "worker";
    const TEMPLATE = "template";
    const CLIENT = "client";
    const WITH = "with";
    
    const HALL_MODEL = "hall_model";
    const WORKER_MODEL = "worker_model";
    const TEMPLATE_MODEL = "template_model";
    const CLIENT_MODEL = "client_model";
    
    const PAST_IGNORE = "past_ignore";
    const ONLY_APPROVED = "only_approved";
    const RESTRICT_TO_MAX_DATE = "restrict_to_max_date";
}