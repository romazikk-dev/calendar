<?php
namespace App\Classes\Getter\Enums;

use App\Classes\Traits\Enumerable;

// use App\Classes\Getter\Enums\Keys;

class Keys{
    
    use Enumerable;
    
    const HALLS = "halls";
    const TEMPLATES = "templates";
    const WORKERS = "workers";
    const CLIENTS = "clients";
    const BOOKINGS = "bookings";
}