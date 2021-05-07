<?php
namespace App\Classes\Suspension\Enums;

use App\Classes\Traits\Enumerable;

// use App\Classes\Suspension\Enums\Types;

class Types{
    use Enumerable;
    
    const COMPLETE = "complete";
    const PERIOD = "period";
    const DISABLE = "disable";
}