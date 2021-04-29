<?php
namespace App\Classes\Enums;

use App\Classes\Traits\Enumerable;

// use App\Classes\Enums\PhoneTypes;

class PhoneTypes{
    
    use Enumerable;
    
    const MOBILE = "mobile";
    const HOME = "home";
    const WORK = "work";
    
}