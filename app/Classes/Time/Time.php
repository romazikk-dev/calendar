<?php
namespace App\Classes\Time;

// use App\Classes\Setting\Enums\Keys as SettingKeys;

class Time extends MainTime{
    
    public function parseStringHourMinutesToMinutes(string $hour_minutes){
        $arr = explode(':', $hour_minutes);
        return ((int)$arr[0] * 60) + (int)$arr[1];
    }
    
    public function composeHourMinuteTimeFromMinutes(int $minutes) {
        $minutes_part = $minutes % 60;
        $hours_part = floor($minutes/60);
        
        if($minutes_part <= 0){
            $minutes_str = '00';
        }elseif($minutes_part > 0 && $minutes_part < 10){
            $minutes_str = '0' . $minutes_part;
        }else{
            $minutes_str = $minutes_part;
        }
        
        if($hours_part <= 0){
            $hours_str = '00';
        }elseif($hours_part > 0 && $hours_part < 10){
            $hours_str = '0' . $hours_part;
        }else{
            $hours_str = $hours_part;
        }
        
        return $hours_str . ':' . $minutes_str;
    }
    
    public function isPeriodInRangeByTimestamp(array $period, array $range, $excluded = true){
        if(count($range) != 2 || count($period) != 2 ||
        empty($range['start']) || empty($range['end']) ||
        empty($period['start']) || empty($period['end']))
            return false;
        
        $crossing = [];
        
        if($excluded === true && $range['start'] <= $period['start'] && $period['start'] < $range['end'])
            $crossing[] = 'start_in';
        if($excluded === false && $range['start'] <= $period['start'] && $period['start'] <= $range['end'])
            $crossing[] = 'start_in';
            
        if($excluded === true && $range['start'] < $period['end'] && $period['end'] <= $range['end'])
            $crossing[] = 'end_in';
        if($excluded === false && $range['start'] <= $period['end'] && $period['end'] <= $range['end'])
            $crossing[] = 'end_in';
            
        if($period['start'] <= $range['start'] && $period['end'] >= $range['end'])
            $crossing[] = 'all_out';
        
        if(empty($crossing))
            return false;
        
        if(in_array('start_in', $crossing) && in_array('end_in', $crossing))
            return 'all_in';
        if(in_array('start_in', $crossing) && count($crossing) == 1)
            return 'only_start_in';
        if(in_array('end_in', $crossing) && count($crossing) == 1)
            return 'only_end_in';
        if(in_array('all_out', $crossing))
            return 'all_out';
        
        // return (
        //     ($range['start'] <= $period['start'] && $period['start'] < $range['end']) ||
        //     ($range['start'] < $period['end'] && $period['end'] <= $range['end']) ||
        //     ($period['start'] <= $range['start'] && $period['end'] >= $range['end'])
        // );
    }
    
}