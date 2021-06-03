<?php

namespace App\Classes\Range;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Booking;
// use App\Models\User;
// use App\Models\Hall;
// use App\Models\Template;
// use App\Models\Worker;
// use App\Exceptions\Api\Calendar\BadRangeException;
// use App\Classes\Range\Enums\Parameters;
// use App\Classes\Setting\Enums\Keys as SettingKeys;

class Range{
    
    // Timestamp format
    protected $start_timestamp = null;
    // Timestamp format
    protected $end_timestamp = null;
    // Date format
    protected $start_date = null;
    // Date format
    protected $end_date = null;
    // Datetime format
    protected $start_datetime = null;
    // Datetime format
    protected $end_datetime = null;
    // String
    protected $view = null;
    // String
    protected $current_server_date = null;
    // String
    protected $current_server_time = null;
    // String
    protected $current_server_timestamp = null;
    // Array
    protected $params = [];
    
    function __construct(
        string $start,
        string $end,
        string $view,
        array $params = []
    ) {
        $this->params = $params;
        $this->view = $view;
        
        $this->current_server_date = date("Y-m-d");
        $this->current_server_time = date("H:i:s");
        $this->current_server_timestamp = time();
        
        // var_dump([
        //     $this->current_server_date,
        //     $this->current_server_time,
        //     $this->current_server_timestamp,
        // ]);
        // die();
        
        //Set timestamps
        $this->start_timestamp = strtotime($start);
        $this->end_timestamp = strtotime($end);
        
        // var_dump([$this->start_timestamp, $this->end_timestamp]);
        // die();
        
        if($this->isWrongTimestamps())
            throw new BadRangeException();
        
        // $this->checkInRegardToMaxBookPointInFutureAndFixStartEnd();
        
        //Set dates
        $this->start_date = date('Y-m-d', $this->start_timestamp);
        $this->end_date = date('Y-m-d', $this->end_timestamp);
        
        //Set datetimes
        $this->start_datetime = $this->start_date . ' 00:00:00';
        $this->end_datetime = $this->end_date . ' 23:59:59';
    }
    
    // protected function checkInRegardToMaxBookPointInFutureAndFixStartEnd(){
    //     // if(!array_key_exists(Parameters::CHECK_MAX_BOOK_POINT_IN_FUTURE, $this->params))
    //     //     return;
    // 
    //     $setting = \Setting::of(SettingKeys::CLIENTS_BOOKING_CALENDAR_MAIN)->getOrPlaceholder();
    //     $max_future_booking_offset = $setting['max_future_booking_offset'];
    //     $max_future_booking_offset_seconds = (60 * 60 * 24) * $max_future_booking_offset;
    //     $max_timestamp = $this->current_server_timestamp + $max_future_booking_offset_seconds;
    //     // var_dump($max_timestamp > $this->start_timestamp);
    // 
    //     if($max_timestamp <= $this->start_timestamp){
    //         $this->start_timestamp = time();
    //         $this->end_timestamp = $max_timestamp;
    //     }else{
    //         if($max_timestamp < $this->end_timestamp){
    //             $this->end_timestamp = $max_timestamp;
    //         }
    //     }
    // 
    // 
    //     // var_dump($max_future_booking_offset);
    //     // var_dump($max_future_booking_offset_seconds);
    //     var_dump([$this->start_timestamp, $this->end_timestamp]);
    //     // var_dump(888);
    //     die();
    // }
    
    protected function isWrongTimestamps(){
        return (is_null($this->start_timestamp) || is_null($this->end_timestamp) ||
        $this->start_timestamp > $this->end_timestamp);
    }
    
    public function getStartDate(){
        return $this->start_date;
    }
    
    public function getEndDate(){
        return $this->end_date;
    }
    
    public function getStartDatetime(){
        return $this->start_datetime;
    }
    
    public function getEndDatetime(){
        return $this->end_datetime;
    }
    
    /**
     * @param \Carbon\Carbon date_carbon
     *
     * @return int - weekday number monday(1), sunday(7)
     */
    public static function getWeekdayFromCarbonInstance($date_carbon){
        $weekday = (int)$date_carbon->format("w");
        return $weekday == 0 ? 7 : $weekday;
    }
    
}
