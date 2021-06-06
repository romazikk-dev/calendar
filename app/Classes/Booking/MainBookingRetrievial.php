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
use App\Models\Holiday;

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
    // Number
    protected $max_timestamp_to_book = null;
    // String
    protected $max_date_to_book = null;
    // String
    protected $max_datetime_to_book = null;
    // Array - all worker holidays
    protected $worker_holidays = [];
    // Array - all halls holidays
    protected $hall_holidays = [];
    
    
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
        
        $this->setMaxDatesToBook();
        $this->setHolidays();
        
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
    
    protected function setHolidays(){
        $getKeyFromCarbon = function($carbon_instance){
            return $carbon_instance->format('Y_m_d');
        };
        
        $getHolidaysFromArray = function($holidays_obj, &$arr_to_fill) use ($getKeyFromCarbon) {
            if(!empty($holidays_obj)){
                $holidays_arr = $holidays_obj->toArray();
                if(!empty($holidays_arr)){
                    // var_dump($worker_holidays_arr);
                    foreach($holidays_arr as $holiday){
                        $from_carbon = \Carbon\Carbon::parse($holiday['from']);
                        $to_carbon = \Carbon\Carbon::parse($holiday['to']);
                        // $key = $holiday_to_carbon->format('Y_m_d');
                        $val = $getKeyFromCarbon($from_carbon);
                        if(!in_array($val, $arr_to_fill))
                            $arr_to_fill[] = $val;
                        // $arr_to_fill[$getKeyFromCarbon($from_carbon)] = $holiday;
                        
                        for($i = 0; $i < 1000; $i++){
                            $from_carbon->addDays(1);
                            if($to_carbon->lt($from_carbon))
                                break;
                                
                            $val = $getKeyFromCarbon($from_carbon);
                            if(!in_array($val, $arr_to_fill))
                                $arr_to_fill[] = $val;
                        }
                    }
                }
            }
        };
        
        $worker_holidays = [];
        $getHolidaysFromArray($this->worker->holidays, $worker_holidays);
        
        $hall_holidays = [];
        $getHolidaysFromArray($this->hall->holidays, $hall_holidays);
        
        $null_holidays = Holiday::where([
            'user_id' => $this->user->id,
            'holidayable_type' => null,
            'holidayable_id' => null,
        ])->get();
        $all_halls_holidays = [];
        $getHolidaysFromArray($null_holidays, $all_halls_holidays);
        
        $hall_holidays = array_unique(array_merge($hall_holidays, $all_halls_holidays));
        
        // $this->holidays = !empty($holidays) ? $holidays : [];
        $this->hall_holidays = !empty($hall_holidays) ? $hall_holidays : [];
        $this->worker_holidays = !empty($worker_holidays) ? $worker_holidays : [];
    }
    
    protected function isWorkerSuspended(string $date){
        return \Suspension::isSuspendedOnDate($this->worker, $date);
        // $suspension = $this->worker->suspension->toArray();
        // if(empty($suspension))
        //     return false;
        // 
        // if($suspension)
        // var_dump($this->worker->suspension->toArray());
        // die();
        // return false;
    }
    
    protected function setMaxDatesToBook(){
        $setting = \Setting::of(SettingsKeys::CLIENTS_BOOKING_CALENDAR_MAIN)->getOrPlaceholder([
            'user_id' => $this->user->id,
        ]);
        
        // var_dump($setting);
        // die();
        
        $max_future_booking_offset = $setting['max_future_booking_offset'];
        $max_future_booking_offset_seconds = (60 * 60 * 24) * $max_future_booking_offset;
        $max_timestamp = time() + $max_future_booking_offset_seconds;
        $max_datetime = date('Y-m-d 23:59:59', $max_timestamp);
        
        $this->max_timestamp_to_book = strtotime($max_datetime);
        $this->max_date_to_book = trim(explode(' ', $max_datetime)[0]);
        $this->max_datetime_to_book = $max_datetime;
        
        // var_dump([
        //     $this->max_timestamp_to_book,
        //     $this->max_date_to_book,
        //     $this->max_datetime_to_book
        // ]);
        // 
        // die();
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
    
    protected function getMaxTimestamp(){
        
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
