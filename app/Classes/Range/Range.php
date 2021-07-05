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
    // String
    protected $current_server_datetime = null;
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
        
        if(preg_match("/^\d{4}\-\d{2}-\d{2}$/", $start))
            $start .= ' 00:00:00';
        if(preg_match("/^\d{4}\-\d{2}-\d{2}$/", $end))
            $end .= ' 23:59:59';
        
        // var_dump($start);
        // die();
        
        $carbon_now = \Carbon\Carbon::now(\Timezone::getCurrentTimezone());
        $carbon_start = \Carbon\Carbon::parse($start, \Timezone::getCurrentTimezone());
        $carbon_end = \Carbon\Carbon::parse($end, \Timezone::getCurrentTimezone());
        
        // $this->current_server_date = date("Y-m-d");
        $this->current_server_date = $carbon_now->format("Y-m-d");
        // $this->current_server_time = date("H:i:s");
        $this->current_server_time = $carbon_now->format("H:i:s");
        $this->current_server_datetime = $carbon_now->format("Y-m-d H:i:s");
        // $this->current_server_timestamp = time();
        $this->current_server_timestamp = $carbon_now->timestamp;
        
        // var_dump([
        //     $this->current_server_date,
        //     $this->current_server_time,
        //     $this->current_server_timestamp,
        // ]);
        // die();
        
        //Set timestamps
        // $this->start_timestamp = strtotime($start);
        $this->start_timestamp = $carbon_start->timestamp;
        // $this->end_timestamp = strtotime($end);
        $this->end_timestamp = $carbon_end->timestamp;
        
        // var_dump([$this->start_timestamp, $this->end_timestamp]);
        // die();
        
        if($this->isWrongTimestamps())
            throw new BadRangeException();
        
        // $this->checkInRegardToMaxBookPointInFutureAndFixStartEnd();
        
        //Set dates
        // $this->start_date = date('Y-m-d', $this->start_timestamp);
        $this->start_date = $carbon_start->format('Y-m-d');
        // $this->end_date = date('Y-m-d', $this->end_timestamp);
        $this->end_date = $carbon_end->format('Y-m-d');
        
        //Set datetimes
        $this->start_datetime = $carbon_start->format('Y-m-d H:i:s');
        $this->end_datetime = $carbon_end->format('Y-m-d H:i:s');
    }
    
    protected function isWrongTimestamps(){
        return (is_null($this->start_timestamp) || is_null($this->end_timestamp) ||
        $this->start_timestamp > $this->end_timestamp);
    }
    
    public function getNowDatetime(){
        return $this->current_server_datetime;
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
