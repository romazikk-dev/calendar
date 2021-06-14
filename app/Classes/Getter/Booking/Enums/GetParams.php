<?php
namespace App\Classes\Getter\Booking\Enums;

use App\Classes\Traits\Enumerable;

// use App\Classes\Getter\Booking\Enums\GetParams;

class GetParams{
    
    use Enumerable;
    
    const ID = "id";
    const OWNER_ID = "owner_id";
    const WITH = "with";
}