<?php
namespace App\Classes\Getter\Booking\Enums;

use App\Classes\Traits\Enumerable;

// use App\Classes\Getter\Booking\Enums\FreeWithEventsParams;

class FreeWithEventsParams{
    
    use Enumerable;
    
    const PER_CLIENT = "per_client";
    const ALL = "all";
}