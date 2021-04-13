<?php

namespace App\Classes\Booking;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Hall;
use App\Models\Template;
use App\Models\Worker;
// use App\Exceptions\Api\Calendar\BadRangeException;

class Range{
    
    /**
     * @var App\Models\User
     */
    protected $user = null;
    
    /**
     * @var App\Models\Booking
     */
    protected $booking = null;
    
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
    // Integer
    protected $hall_id = null;
    // Integer
    protected $worker_id = null;
    // Integer
    protected $template_id = null;
    // String
    protected $view = null;
    
    function __construct(
        User $user,
        int $hall_id,
        int $worker_id,
        int $template_id,
        string $view,
        string $start,
        string $end
    ) {
        // parent::__construct();
        $this->user = $user;
        $this->hall_id = $hall_id;
        $this->worker_id = $worker_id;
        $this->template_id = $template_id;
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
        
        $this->composeBookingModel();
    }
    
    protected function composeBookingModel(){
        $this->booking = Booking::user($this->user->id)
            ->where('time', '>=', $this->start_date)
            ->where('time', '<=', $this->end_date)
            ->where('hall_id', '=', $this->hall_id)
            ->where('worker_id', '=', $this->worker_id)
            ->where('template_id', '=', $this->template_id)
            ->orderBy('time', 'ASC');
    }
    
    public function getDateTimeKeyArray(){
        $bookings = $this->booking->get();
        
        if($bookings->isEmpty())
            return [];
            
        $booked_itms = [];
        foreach($bookings as $booking){
            $booking_date_index = \Carbon\Carbon::parse($booking['time'])->format('Y_m_d');
            $booking_hour_index = \Carbon\Carbon::parse($booking['time'])->format('H_i');
            $booked_itms[$booking_date_index][$booking_hour_index] = $booking;
        }
        
        return $booked_itms;
    }
    
    public function getBookingsModel(){
        return $this->booking;
    }
    
    public function getBookingsCollection(){
        return $this->booking->get();
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
    
}
