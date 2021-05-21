<?php
namespace App\Classes\Setting\Settings\CustomFields;

use App\Classes\Setting\Enums\Keys;
// use App\Classes\Language\Enums\Abriviations;
use App\Classes\Setting\Settings\Setting;

class MainCustomFields extends Setting{
    protected $aliases = null;
    protected $custom_fields = null;
    
    function __construct($setting_key){
        parent::__construct($setting_key);
        $this->setAliases();
        $this->setCustomFields();
    }
    
    protected function setAliases(){
        $files_folder = app_path('Classes/Setting/Settings/CustomFields/custom_fields/');
        $this->aliases = [
            Keys::CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES => $files_folder . 'clients_booking_calendar.php',
        ];
    }
    
    protected function setCustomFields(){
        if(!array_key_exists($this->setting_key, $this->aliases))
            return null;
            
        $this->custom_fields = include_once($this->aliases[$this->setting_key]);
    }
    
}