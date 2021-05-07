<?php
namespace App\Classes\Setting\Enums;

use App\Classes\Traits\Enumerable;

class Keys
{
    use Enumerable;
    
    const DEFAULT_BUSINESS_HOURS = "default_bussiness_hours";
    const WORKER_DEFAULT_BUSINESS_HOURS = "worker_default_bussiness_hours";
}