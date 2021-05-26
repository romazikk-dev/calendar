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
use App\Classes\Enums\Weekdays;
use App\Classes\Setting\Enums\Keys as SettingsKeys;

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
        
        // $this->setBusinessHours();
        
        // var_dump($this->hall_business_hours);
        // die();
        
        $this->hall_business_hours = json_decode($this->hall->business_hours);
        
        // var_dump($this->hall_business_hours);
        // die();
        
        // $this->hall_business_hours = json_decode($this->hall->business_hours, true);
        // $this->hall_business_hours = \Setting::of(SettingsKeys::HALL_DEFAULT_BUSINESS_HOURS)->arrange($this->hall->business_hours);
        
        $this->composeBookingModel();
    }
    
    protected function parseArrayBusinessHoursToIntWeekKey($business_hours_array){
        $hall_business_hours = $business_hours_array;
        $parsed_hall_business_hours = [];
        foreach(Weekdays::all() as $k => $v){
            $parsed_hall_business_hours[] = $hall_business_hours[$v];
        }
        return $parsed_hall_business_hours;
    }
    
    protected function getHallBusinessHoursFromCarbonDate($date_carbon){
        // Weekdays
        // var_dump(array_values(Weekdays::all()));
        // var_dump($this->hall_business_hours[1]);
        // var_dump(Range::getWeekdayFromCarbonInstance($date_carbon));
        // die();
        $business_hour_index = (Range::getWeekdayFromCarbonInstance($date_carbon)) - 1;
        $weekdays = array_values(Weekdays::all());
        
        // var_dump($weekdays);
        // var_dump($business_hour_index);
        // var_dump($this->hall_business_hours);
        // var_dump($this->hall_business_hours->{$weekdays[$business_hour_index]});
        // die();
        
        return $this->hall_business_hours->{$weekdays[$business_hour_index]};
        // return $this->hall_business_hours[$business_hour_index];
    }
    
    protected function isByClient($client_id = null){
        $out = !is_null($this->client);
        if(!is_null($client_id) && is_numeric($client_id))
            return $out && $this->client->id == $client_id;
        return $out;
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
        
        if(!$this->isByClient()){
            $this->booking->approved();
        }
        
        // if($this->isByClient()){
        //     $this->booking->byClient($this->client->id);
        // }else{
        //     $this->booking->approved();
        // }
            
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
