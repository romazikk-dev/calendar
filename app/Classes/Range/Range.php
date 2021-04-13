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
    
    function __construct(
        string $start,
        string $end,
        string $view
    ) {
        $this->view = $view;
        
        //Set timestamps
        $this->start_timestamp = strtotime($start);
        $this->end_timestamp = strtotime($end);
        if($this->isWrongTimestamps())
            throw new BadRangeException();
        
        //Set dates
        $this->start_date = date('Y-m-d', $this->start_timestamp);
        $this->end_date = date('Y-m-d', $this->end_timestamp);
        
        //Set datetimes
        $this->start_datetime = $this->start_date . ' 00:00:00';
        $this->end_datetime = $this->end_date . ' 23:59:59';
    }
    
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
