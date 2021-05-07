<?php
namespace App\Classes\PhonePicker\Enums;

use App\Classes\Traits\Enumerable;

// use App\Classes\Enums\PhoneTypes;

class Types{
    
    use Enumerable;
    
    const MOBILE = "mobile";
    const HOME = "home";
    const WORK = "work";
    const CUSTOM = "custom";
    
}