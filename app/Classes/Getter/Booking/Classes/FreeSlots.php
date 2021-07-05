<?php

namespace App\Classes\Getter\Booking\Classes;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;

// use App\Classes\Getter\Template\Template;
// use App\Models\Booking;
// use App\Classes\Getter\Booking\Enums\GetParams;
use App\Models\User;
use App\Models\Hall;
use App\Models\Template;
use App\Models\Worker;
use App\Scopes\UserScope;
use App\Classes\Range\Range;
use App\Classes\Setting\Enums\Keys as SettingsKeys;
use App\Classes\Getter\Booking\Enums\Params;
use App\Classes\Enums\Weekdays;
use App\Models\Holiday;
use App\Exceptions\BookingGetterBadParametersException;

// use App\Classes\Getter\Booking\Classes\MainBookingGetter;

class FreeSlots extends MainBookingGetter{
    
    protected $max_timestamp, $max_date, $max_datetime;
    protected $hall_model, $worker_model, $template_model;
    protected $hall_holidays, $worker_holidays;
    protected $hall_business_hours;
        
    function __construct(
        User $owner,
        Range $range,
        Hall $hall_model,
        Worker $worker_model,
        array $params = []
    ) {
        $params[Params::HALL] = $hall_model->id;
        $params[Params::WORKER] = $worker_model->id;
        parent::__construct($owner, $range, $params);
        
        $this->hall_model = $hall_model;
        $this->worker_model = $worker_model;
        
        if(!$this->isWorkerBelongsToHall())
            throw new BookingGetterBadParametersException("Worker model do not belongs to Hall model in ".self::class."::".__FUNCTION__);
            
        $this->setHolidays();
        
        $this->hall_business_hours = json_decode($this->hall_model->business_hours);
        
        if(!empty($params[Params::RESTRICT_TO_MAX_DATE]) && $params[Params::RESTRICT_TO_MAX_DATE] === true){
            $this->setMaxDate();
        }else{
            $this->max_timestamp = null;
            $this->max_date = null;
            $this->max_datetime = null;
        }
    }
    
    protected function isWorkerBelongsToHall(){
        if(empty($this->hall_model->workers))
            return false;
        
        foreach ($this->hall_model->workers as $k => $v)
            if($v->id == $this->worker_model->id)
                return true;
                
        return false;
    }
    
    public function get(){
        $bookings = $this->getBookingsAsDateTimeKeyArray();
        
        $start_date_carbon = \Carbon\Carbon::parse($this->range->getStartDate());
        $end_date_carbon = \Carbon\Carbon::parse($this->range->getEndDate());
        
        if(!empty($params[Params::RESTRICT_TO_MAX_DATE]))
            $max_date_carbon = \Carbon\Carbon::parse($this->max_datetime);
        
        $output = [];
        do{
            $weekday = Range::getWeekdayFromCarbonInstance($start_date_carbon);
            
            $bookable = true;
            
            $datetime_of_item = $start_date_carbon->format("Y-m-d H:i:s");
            $holiday_val = $start_date_carbon->format("Y_m_d");
            if(
                $bookable === true &&
                (
                    \Suspension::isSuspendedOnDate($this->worker_model, $datetime_of_item) ||
                    !empty($this->worker_holidays) && in_array($holiday_val, $this->worker_holidays)
                )
            )
                $bookable = false;
            
            $is_weekend = false;
            if(\Suspension::isSuspendedOnDate($this->hall_model, $datetime_of_item) ||
            (!empty($this->hall_holidays) && in_array($holiday_val, $this->hall_holidays)))
                $is_weekend = true;
            
            //Set initial itm
            $itm = [
                'year' => $start_date_carbon->format("Y"),
                'month' => $start_date_carbon->format("m"),
                'day' => $start_date_carbon->format("d"),
                'is_weekend' => $is_weekend,
                'weekday' => $weekday,
                'bookable' => $bookable,
                'items' => null,
            ];
            
            $itm_date_index = $itm['year'] . '_' . $itm['month'] . '_' . $itm['day'];
            $itm_date = $itm['year'] . '-' . $itm['month'] . '-' . $itm['day'];
            
            //Set itm data related to hall
            $hall_business_hours = $this->getHallBusinessHoursForCarbonDate($start_date_carbon);
            
            $itm['start'] = !empty($hall_business_hours->start_hour) ? $hall_business_hours->start_hour : null;
            $itm['end'] = !empty($hall_business_hours->end_hour) ? $hall_business_hours->end_hour : null;
            if(!empty($hall_business_hours->is_weekend)){
                $itm['bookable'] = false;
                $itm['is_weekend'] = true;
                $itm['items'] = null;
                $output[] = $itm;
                $start_date_carbon->addDays(1);
                continue;
            }
            
            $hall_start_time = $hall_business_hours->start_hour;
            $hall_end_time = $hall_business_hours->end_hour;
            
            if(!empty($bookings) && !empty($bookings[$itm_date_index])){
                $items = [];
                $booking = $bookings[$itm_date_index];
                
                //Set hall start - datetime format
                $hall_start_datetime = $itm_date . ' ' . $hall_start_time;
                if(count(explode(':', $hall_start_time)) <= 2)
                    $hall_start_datetime .= ':00';
                
                //Set hall end - datetime format
                $hall_end_datetime = $itm_date . ' ' . $hall_end_time;
                if(count(explode(':', $hall_end_time)) <= 2)
                    $hall_end_datetime .= ':00';
                
                //Set start and end carbon instances
                $start_carbon = \Carbon\Carbon::parse($hall_start_datetime);
                $end_carbon = \Carbon\Carbon::parse($hall_end_datetime);
                
                // var_dump($this->exclude_ids);
                // die();
                
                foreach($booking as $k=>$v){
                    $booking_carbon = \Carbon\Carbon::parse($v->time);
                    $template_duration = !empty($v->custom_duration) ?
                        $v->custom_duration : $v->templateWithoutUserScope->duration;
                    // $template_duration = $v->templateWithoutUserScope->duration + 900;
                    
                    if($v->approved && $start_carbon->lte($booking_carbon)){
                        if($start_carbon->lt($booking_carbon)){
                            $items[] = [
                                'type' => 'free',
                                'from' => $start_carbon->format('H:i'),
                                'to' => $booking_carbon->format('H:i'),
                            ];
                        }
                        $start_carbon = clone $booking_carbon;
                        $start_carbon->addMinutes($template_duration);
                    }
                }
                
                if($start_carbon->lt($end_carbon))
                    $items[] = [
                        'type' => 'free',
                        'from' => $start_carbon->format('H:i'),
                        'to' => $end_carbon->format('H:i'),
                    ];
                
                if(!empty($items))
                    $itm['items'] = $items;
                    
            }else{
                $itm['items'] = [
                    [
                        'type' => 'free',
                        'from' => $hall_start_time,
                        'to' => $hall_end_time,
                        'not_approved_bookings' => null
                    ]
                ];
            }
            
            $output[] = $itm;
            $start_date_carbon->addDays(1);
        }while($start_date_carbon->lte($end_date_carbon));
        
        return $output;
    }
    
    protected function getHallBusinessHoursForCarbonDate($date_carbon){
        $business_hour_index = (Range::getWeekdayFromCarbonInstance($date_carbon)) - 1;
        $weekdays = array_values(Weekdays::all());
        
        return $this->hall_business_hours->{$weekdays[$business_hour_index]};
    }
    
    protected function setMaxDate(){
        $setting = \Setting::of(SettingsKeys::CLIENTS_BOOKING_CALENDAR_MAIN)->getOrPlaceholder([
            'user_id' => $this->owner->id,
        ]);
        
        $max_future_booking_offset = $setting['max_future_booking_offset'];
        $max_future_booking_offset_seconds = (60 * 60 * 24) * $max_future_booking_offset;
        $max_timestamp = time() + $max_future_booking_offset_seconds;
        $max_datetime = date('Y-m-d 23:59:59', $max_timestamp);
        
        $this->max_timestamp = strtotime($max_datetime);
        $this->max_date = trim(explode(' ', $max_datetime)[0]);
        $this->max_datetime = $max_datetime;
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
        $getHolidaysFromArray($this->worker_model->holidays, $worker_holidays);
        
        $hall_holidays = [];
        $getHolidaysFromArray($this->hall_model->holidays, $hall_holidays);
        
        $null_holidays = Holiday::where([
            'user_id' => $this->owner->id,
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
    
}