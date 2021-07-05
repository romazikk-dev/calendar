<?php
namespace App\Classes\Time;

// use App\Classes\Setting\Enums\Keys as SettingKeys;

class Time extends MainTime{
    
    public function parseStringHourMinutesToMinutes(string $hour_minutes){
        $arr = explode(':', $hour_minutes);
        return ((int)$arr[0] * 60) + (int)$arr[1];
    }
    
}