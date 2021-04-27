<?php

namespace App\Classes\Suspension;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Booking;
// use App\Models\User;
// use App\Models\Hall;
// use App\Models\Template;
// use App\Models\Worker;
// use App\Exceptions\Api\Calendar\BadRangeException;

class Range{
    
    protected $start = null;
    protected $end = null;
    
    function __construct(string $start, string $end) {
        $this->start = $start;
        $this->end = $end;
    }
    
}