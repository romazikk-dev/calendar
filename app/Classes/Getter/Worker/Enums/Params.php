<?php
namespace App\Classes\Getter\Worker\Enums;

use App\Classes\Traits\Enumerable;

// use App\Classes\Getter\Worker\Enums\Params;

class Params{
    
    use Enumerable;
    
    const ID = "id";
    const TEMPLATE_ID = "template_id";
    const HALL_ID = "hall_id";
    const OWNER_ID = "owner_id";
    const WITH = "with";
    const ONLY_FIRST_ITEM = "only_first_items";
}