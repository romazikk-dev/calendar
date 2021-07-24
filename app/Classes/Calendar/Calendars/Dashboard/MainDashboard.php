<?php
namespace App\Classes\Calendar\Calendars\Dashboard;

class MainDashboard{
    
    protected $view;
    
    function __construct() {
        $this->view = new View();
    }
    
}