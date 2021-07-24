<?php

namespace App\Classes\Calendar;

use App\Classes\Calendar\Calendars\Dashboard\Dashboard;

class MainCalendar{
    
    protected $dashboard = null;
    
    function __construct() {
        $this->dashboard = new Dashboard();
    }
    
}