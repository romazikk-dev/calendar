<?php

namespace App\Classes\Booking;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Hall;
use App\Models\Template;
use App\Models\Worker;
use App\Models\Client;
use App\Classes\Range\Range;
use App\Scopes\UserScope;
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
    // Client model
    protected $client = null;
    // Template model
    protected $template = null;
    // Array - Hall business hours
    protected $hall_business_hours = null;
    
    
    function __construct(
        User $user,
        Hall $hall,
        Worker $worker,
        Template $template,
        Range $range,
        Client $client = null
    ) {
        // parent::__construct();
        $this->user = $user;
        $this->hall = $hall;
        $this->worker = $worker;
        $this->template = $template;
        $this->range = $range;
        
        if(!is_null($client))
            $this->client = $client;
        
        $this->hall_business_hours = json_decode($this->hall->business_hours);
        
        $this->composeBookingModel();
    }
    
    protected function getHallBusinessHoursFromCarbonDate($date_carbon){
        $business_hour_index = (Range::getWeekdayFromCarbonInstance($date_carbon)) - 1;
        return $this->hall_business_hours[$business_hour_index];
    }
    
    protected function isByClient(){
        return !is_null($this->client);
    }
    
    protected function composeBookingModel(){
        // var_dump([
        //     $this->range->getStartDatetime(), $this->range->getEndDatetime()
        // ]);
        // die();
        
        // var_dump($this->user);
        // die();
        
        $this->booking = Booking::withoutGlobalScope(UserScope::class)
            ->byUser($this->user->id)
            ->where('time', '>=', $this->range->getStartDatetime())
            ->where('time', '<=', $this->range->getEndDatetime())
            ->where('hall_id', '=', $this->hall->id)
            ->where('worker_id', '=', $this->worker->id)
            ->where('template_id', '=', $this->template->id)
            ->orderBy('time', 'ASC');
            
        if($this->isByClient()){
            $this->booking->byClient($this->client->id);
        }else{
            $this->booking->approved();
        }
            
        // var_dump($this->booking->get());
        // die();
    }
    
    protected function getBookingsAsDateTimeKeyArray($only_approved = false){
        $booking = clone $this->booking;
        if($only_approved)
            $booking->where('approved', '=', 1);
        $bookings = $booking->get();
        
        if($bookings->isEmpty())
            return [];
            
        $booked_itms = [];
        
        // var_dump($bookings);
        // die();
        
        foreach($bookings as $booking){
            $booking_date_index = \Carbon\Carbon::parse($booking['time'])->format('Y_m_d');
            $booking_hour_index = \Carbon\Carbon::parse($booking['time'])->format('H_i');
            $booked_itms[$booking_date_index][$booking_hour_index] = $booking;
        }
        
        return $booked_itms;
    }
    
}
