<?php

namespace App\Classes\Booking;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Hall;
use App\Models\Template;
use App\Models\Worker;
use App\Classes\Range\Range;
// use App\Exceptions\Api\Calendar\BadRangeException;

class MainBookingRetrievial{
    
    // User model
    protected $user = null;
    // Booking model
    protected $booking = null;
    // Range model
    protected $range = null;
    // Hall model
    protected $hall = null;
    // Worker model
    protected $worker = null;
    // Template model
    protected $template = null;
    // Array - Hall business hours
    protected $hall_business_hours = null;
    
    
    function __construct(
        User $user,
        Hall $hall,
        Worker $worker,
        Template $template,
        Range $range
    ) {
        // parent::__construct();
        $this->user = $user;
        $this->hall = $hall;
        $this->worker = $worker;
        $this->template = $template;
        $this->range = $range;
        
        $this->hall_business_hours = json_decode($this->hall->business_hours);
        
        $this->composeBookingModel();
    }
    
    protected function getHallBusinessHoursFromCarbonDate($date_carbon){
        $business_hour_index = (Range::getWeekdayFromCarbonInstance($date_carbon)) - 1;
        return $this->hall_business_hours[$business_hour_index];
    }
    
    protected function composeBookingModel(){
        $this->booking = Booking::user($this->user->id)
            ->where('time', '>=', $this->range->getStartDate())
            ->where('time', '<=', $this->range->getEndDate())
            ->where('hall_id', '=', $this->hall->id)
            ->where('worker_id', '=', $this->worker->id)
            ->where('template_id', '=', $this->template->id)
            ->orderBy('time', 'ASC');
    }
    
    protected function getBookingsAsDateTimeKeyArray($only_approved = false){
        $booking = clone $this->booking;
        if($only_approved)
            $booking->where('approved', '=', 1);
        $bookings = $booking->get();
        
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
    
}
