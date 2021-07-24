<?php
namespace App\Classes\Calendar\Calendars\Dashboard;

use App\Classes\Calendar\Calendars\Dashboard\Enums\Views;
use App\Classes\Calendar\Calendars\Enums\DashboardCookieNames;

class View{
    
    private $view;
    
    function __construct() {
        $this->setView();
    }
    
    private function setView(){
        $view = !empty($_COOKIE[DashboardCookieNames::VIEW]) ? $_COOKIE[DashboardCookieNames::VIEW] : Views::MONTH;
        $this->view = in_array($view, Views::all()) ? $view : Views::MONTH;
        return $this;
    }
    
    public function getViews(){
        return Views::all();
    }
    
    public function getCookieName(){
        return DashboardCookieNames::VIEW;
    }
    
    public function getView(){
        return $this->view;
    }
    
}