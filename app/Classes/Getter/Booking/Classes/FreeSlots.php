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
use App\Classes\DurationRange\Enums\GetParams as DurationRangeParams;

use App\Classes\Getter\Worker\Enums\Params as WorkerGetterParams;

// use App\Classes\Getter\Booking\Classes\MainBookingGetter;

class FreeSlots extends MainBookingGetter{
    
    protected $max_timestamp, $max_date, $max_datetime;
    protected $worker_result;
    protected $worker_holidays, $hall_holidays;
    protected $hall_business_hours;
    protected $with_events_per_client;
        
    function __construct(
        Range $range,
        array $params = []
    ) {
        if(empty($params[Params::WORKER]) || !is_numeric($params[Params::WORKER]))
            throw new BookingGetterBadParametersException("Worker param is required for retrieving free slots. Error in ".self::class."::".__FUNCTION__);
        
        if(!empty($params[Params::WITH_EVENTS_PER_CLIENT]) && is_numeric($params[Params::WITH_EVENTS_PER_CLIENT]))
            $this->with_events_per_client = (int)$params[Params::WITH_EVENTS_PER_CLIENT];
        
        // $this->worker_result = Worker::byId($params[Params::WORKER])->with('hallsWithoutUserScope')->first();
        // $this->worker_result = $this->getWorkerById();
        $worker_getter_params = [
            WorkerGetterParams::ID => $params[Params::WORKER],
            WorkerGetterParams::ONLY_FIRST_ITEM => true,
        ];
        if(!empty($params[Params::OWNER]) && is_numeric($params[Params::OWNER]))
            $worker_getter_params[WorkerGetterParams::OWNER_ID] = (int)$params[Params::OWNER];
        // $this->worker_result = Worker::find($params[Params::WORKER]);
        $this->worker_result = \Getter::workers()->get($worker_getter_params);
        
        // var_dump($this->worker_result->id);
        // die();
            
        if(empty($this->worker_result))
            throw new BookingGetterBadParametersException("Worker by param do not exists. Error in ".self::class."::".__FUNCTION__);
        
        // unset($params[Params::HALL], $params[Params::TEMPLATE], $params[Params::CLIENT]);
        parent::__construct($range, $params);
        
        // var_dump($this->worker_result->holidays);
        // die();
        
        $holidays = \Holiday::ofWorker($this->worker_result, true);
        
        // var_dump(111);
        // var_dump($holidays);
        // die();
        
        $this->worker_holidays = $holidays['worker'];
        $this->hall_holidays = $holidays['hall'];
        
        // $this->hall_business_hours = json_decode($this->hall_model->business_hours);
        $this->hall_business_hours = json_decode($this->worker_result->hallsWithoutUserScope->first()->business_hours);
        
        if(!empty($params[Params::RESTRICT_TO_MAX_DATE]) && $params[Params::RESTRICT_TO_MAX_DATE] === true){
            $this->setMaxDate();
        }else{
            $this->max_timestamp = null;
            $this->max_date = null;
            $this->max_datetime = null;
        }
    }
    
    // protected function getWorker(){
    // 
    // }
    
    // public function get(){
    //     $bookings = $this->getBookingsAsDateTimeKeyArray();
    // 
    //     // var_dump($bookings);
    //     // die();
    //     $min_duration = \DurationRange::get(DurationRangeParams::START);
    //     $max_duration = \DurationRange::get(DurationRangeParams::END);
    // 
    //     $start_date_carbon = \Carbon\Carbon::parse($this->range->getStartDate());
    //     $end_date_carbon = \Carbon\Carbon::parse($this->range->getEndDate());
    // 
    //     if(!empty($params[Params::RESTRICT_TO_MAX_DATE]))
    //         $max_date_carbon = \Carbon\Carbon::parse($this->max_datetime);
    // 
    //     $output = [];
    //     do{
    //         $weekday = Range::getWeekdayFromCarbonInstance($start_date_carbon);
    // 
    //         $datetime_of_item = $start_date_carbon->format("Y-m-d H:i:s");
    //         $holiday_val = \Holiday::getKeyOfCarbonInstance($start_date_carbon);
    // 
    //         $bookable = true;
    //         if(\Suspension::isSuspendedOnDate($this->worker_result, $datetime_of_item) ||
    //         !empty($this->worker_holidays) && in_array($holiday_val, $this->worker_holidays))
    //             $bookable = false;
    // 
    //         $is_weekend = false;
    //         if(\Suspension::isSuspendedOnDate($this->worker_result->hallsWithoutUserScope->first(), $datetime_of_item) ||
    //         (!empty($this->hall_holidays) && in_array($holiday_val, $this->hall_holidays)))
    //             $is_weekend = true;
    // 
    //         //Set initial itm
    //         $itm = [
    //             'year' => $start_date_carbon->format("Y"),
    //             'month' => $start_date_carbon->format("m"),
    //             'day' => $start_date_carbon->format("d"),
    //             'type' => 'free',
    //             'is_weekend' => $is_weekend,
    //             'weekday' => $weekday,
    //             'bookable' => $bookable,
    //             'items' => null,
    //         ];
    // 
    //         $itm_date_index = $itm['year'] . '_' . $itm['month'] . '_' . $itm['day'];
    //         $itm_date = $itm['year'] . '-' . $itm['month'] . '-' . $itm['day'];
    // 
    //         //Set itm data related to hall
    //         $hall_business_hours = $this->getHallBusinessHoursForCarbonDate($start_date_carbon);
    // 
    //         $itm['start'] = !empty($hall_business_hours->start_hour) ? $hall_business_hours->start_hour : null;
    //         $itm['end'] = !empty($hall_business_hours->end_hour) ? $hall_business_hours->end_hour : null;
    //         if(!empty($hall_business_hours->is_weekend)){
    //             $itm['bookable'] = false;
    //             $itm['is_weekend'] = true;
    //             $itm['items'] = null;
    //             $output[] = $itm;
    //             $start_date_carbon->addDays(1);
    //             continue;
    //         }
    // 
    //         $hall_start_time = $hall_business_hours->start_hour;
    //         $hall_end_time = $hall_business_hours->end_hour;
    // 
    //         //Set hall start - datetime format
    //         $hall_start_datetime = $itm_date . ' ' . $hall_start_time;
    //         if(count(explode(':', $hall_start_time)) <= 2)
    //             $hall_start_datetime .= ':00';
    // 
    //         //Set hall end - datetime format
    //         $hall_end_datetime = $itm_date . ' ' . $hall_end_time;
    //         if(count(explode(':', $hall_end_time)) <= 2)
    //             $hall_end_datetime .= ':00';
    // 
    //         //Set start and end carbon instances
    //         $start_carbon = \Carbon\Carbon::parse($hall_start_datetime);
    //         $end_carbon = \Carbon\Carbon::parse($hall_end_datetime);
    //         $free_times = [];
    // 
    //         if(!empty($bookings) && !empty($bookings[$itm_date_index])){
    //             $items = [];
    //             $booking = $bookings[$itm_date_index];
    // 
    //             foreach($booking as $k=>$v){
    //                 $booking_carbon = \Carbon\Carbon::parse($v->time);
    //                 $template_duration = !empty($v->custom_duration) ?
    //                     $v->custom_duration : $v->templateWithoutUserScope->duration;
    // 
    //                 if($v->approved && $start_carbon->lte($booking_carbon)){
    //                     if($start_carbon->lt($booking_carbon))
    //                         $items[] = $this->composeFreeItem($start_carbon, $booking_carbon);
    //                     $start_carbon = clone $booking_carbon;
    //                     $start_carbon->addMinutes($template_duration);
    //                 }
    //             }
    // 
    //             if($start_carbon->lt($end_carbon))
    //                 $items[] = $this->composeFreeItem($start_carbon, $end_carbon);
    // 
    //             if(!empty($items))
    //                 $itm['items'] = $items;
    // 
    //         }else{
    //             $itm['items'] = [$this->composeFreeItem($start_carbon, $end_carbon)];
    //         }
    // 
    //         $output[] = $itm;
    //         $start_date_carbon->addDays(1);
    //     }while(
    //         $start_date_carbon->lte($end_date_carbon) &&
    //         (
    //             empty($max_date_carbon) ||
    //             (!empty($max_date_carbon) && $start_date_carbon->lte($max_date_carbon))
    //         )
    //     );
    // 
    //     return $output;
    // }
    
    public function get(){
        $bookings = $this->getBookingsAsDateTimeKeyArray();
    
        // var_dump($bookings);
        // die();
        
        $min_duration = \DurationRange::get(DurationRangeParams::START);
        $max_duration = \DurationRange::get(DurationRangeParams::END);
    
        $start_date_carbon = \Carbon\Carbon::parse($this->range->getStartDate());
        $end_date_carbon = \Carbon\Carbon::parse($this->range->getEndDate());
    
        if(!empty($params[Params::RESTRICT_TO_MAX_DATE]))
            $max_date_carbon = \Carbon\Carbon::parse($this->max_datetime);
    
        $output = [];
        do{
            $weekday = Range::getWeekdayFromCarbonInstance($start_date_carbon);
    
            $datetime_of_item = $start_date_carbon->format("Y-m-d H:i:s");
            $holiday_val = \Holiday::getKeyOfCarbonInstance($start_date_carbon);
    
            $bookable = true;
            if(\Suspension::isSuspendedOnDate($this->worker_result, $datetime_of_item) ||
            !empty($this->worker_holidays) && in_array($holiday_val, $this->worker_holidays))
                $bookable = false;
    
            $is_weekend = false;
            if(\Suspension::isSuspendedOnDate($this->worker_result->hallsWithoutUserScope->first(), $datetime_of_item) ||
            (!empty($this->hall_holidays) && in_array($holiday_val, $this->hall_holidays)))
                $is_weekend = true;
    
            //Set initial itm
            $itm = [
                'year' => $start_date_carbon->format("Y"),
                'month' => $start_date_carbon->format("m"),
                'day' => $start_date_carbon->format("d"),
                'type' => 'free',
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
    
            //Set hall start - datetime format
            $hall_start_datetime = $itm_date . ' ' . $hall_start_time;
            if(count(explode(':', $hall_start_time)) <= 2)
                $hall_start_datetime .= ':00';
    
            //Set hall end - datetime format
            $hall_end_datetime = $itm_date . ' ' . $hall_end_time;
            if(count(explode(':', $hall_end_time)) <= 2)
                $hall_end_datetime .= ':00';
    
            //Set start and end carbon instances
            $hall_start_carbon = \Carbon\Carbon::parse($hall_start_datetime);
            $start_carbon = $hall_start_carbon->copy();
            $end_carbon = \Carbon\Carbon::parse($hall_end_datetime);
            
            if(!empty($bookings) && !empty($bookings[$itm_date_index])){
                $items = [];
                $booking = $bookings[$itm_date_index];
                
                // Merge all crossing bookings(events)
                $merged_booking = $this->getEventsWithMergedCrossing($booking);
                
                // if($itm['day'] == 19){
                //     var_dump($merged_booking);
                //     die();
                // }
                
                // Bookings(events) ids to track added (event) items
                $added_bookings_ids = [];
                
                // Closure, puts unique event`s ids into `$added_bookings_ids`
                // for future track uniqueness added items
                $put_ids_in_added_bookings_ids = function($events) use (&$added_bookings_ids) {
                    foreach($events as $event){
                        if(!in_array($event['id'], $added_bookings_ids))
                            $added_bookings_ids[] = $event['id'];
                    }
                };
                
                // Closure gets unique events(bookings) from given range
                // uses `$added_bookings_ids` to track uniqueness
                $get_unique_booking_events_from_range = function($range, $events) use (
                    &$added_bookings_ids, $put_ids_in_added_bookings_ids
                ) {
                    $booking_events = $this->getInnerEventsOfRange([
                        "start" => $range['start'],
                        "end" => $range['end'],
                    ], $events, ['exclude_ids' => $added_bookings_ids]);
                    
                    if(!empty($booking_events))
                        $put_ids_in_added_bookings_ids($booking_events);
                        
                    return $booking_events;
                };
                
                // Closure, puts events(bookings) into `$items`
                $put_events_into_items = function($events, $type = 'event') use (&$items) {
                    if(!empty($events)){
                        if(!is_array($events))
                            $events = [$events];
                        foreach($events as $k => $v){
                            $v['type'] = $type;
                            $items[] = $v;
                        }
                    }
                };
                
                // if exists approved events(bookings)
                if(!empty($merged_booking)){
                    foreach($merged_booking as $k => $v){
                        $start_booking_carbon = \Carbon\Carbon::parse($v['start']);
                        $end_booking_carbon = \Carbon\Carbon::parse($v['end']);
                        
                        // Only puts items which are less then `$start_carbon`
                        if($start_booking_carbon->lte($hall_start_carbon)){
                            if(!empty($this->with_events_per_client)){
                                $booking_events = $get_unique_booking_events_from_range([
                                    "start" => $start_booking_carbon->copy()->startOfDay()->timestamp,
                                    "end" => $end_booking_carbon->timestamp,
                                ], $booking);
                                $put_events_into_items($booking_events);
                            }
                            if($hall_start_carbon->lte($end_booking_carbon)){
                                $start_carbon = $end_booking_carbon->copy();
                            }
                            continue;
                        }else{
                            // If `$start_carbon` not changed yet and booking(event) start date
                            // is more then hall start date and only if events per client are needed
                            if($start_carbon->eq($hall_start_carbon) && !empty($this->with_events_per_client)){
                                $booking_events = $get_unique_booking_events_from_range([
                                    "start" => $start_carbon->copy()->startOfDay()->timestamp,
                                    "end" => $start_carbon->timestamp,
                                ], $booking);
                                $put_events_into_items($booking_events);
                            }
                        }
                        
                        if($start_carbon->eq($start_booking_carbon)){
                            if(!empty($this->with_events_per_client)){
                                $range_start_carbon = $start_booking_carbon->copy();
                                if($end_booking_carbon->lt($end_carbon)){
                                    $range_end_carbon = $end_booking_carbon->copy();
                                }else{
                                    $range_end_carbon = $end_carbon->endOfDay()->copy();
                                }
                                
                                $booking_events = $get_unique_booking_events_from_range([
                                    "start" => $range_start_carbon->timestamp,
                                    "end" => $range_end_carbon->timestamp,
                                ], $booking);
                                $put_events_into_items($booking_events);
                                
                                if($end_carbon->lte($end_booking_carbon))
                                    break;
                            }
                            
                            if($end_carbon->lte($end_booking_carbon))
                                break;
                            
                            $start_carbon = $end_booking_carbon->copy();
                            continue;
                        }
                        
                        if($start_carbon->lt($start_booking_carbon)){
                            // Set range dates for free item
                            $range_start_carbon = $start_carbon->copy();
                            if($end_carbon->lte($start_booking_carbon)){
                                $range_end_carbon = $end_carbon->copy();
                            }else{
                                $range_end_carbon = $start_booking_carbon->copy();
                            }
                            
                            // If we do not need client`s events, just create free item
                            if(empty($this->with_events_per_client)){
                                $items[] = $this->composeFreeItem($range_start_carbon, $range_end_carbon);
                                
                                // If end time of hall less then start or less then end date
                                //  of iterable item(merged booking), break the iteration
                                if($end_carbon->lte($start_booking_carbon) || $end_carbon->lte($end_booking_carbon))
                                    break;
                                    
                                // $start_carbon = $range_end_carbon->copy();
                                $start_carbon = $end_booking_carbon->copy();
                                continue;
                            }
                            
                            // Get bookings(events) for free item
                            $booking_events = $get_unique_booking_events_from_range([
                                "start" => $range_start_carbon->timestamp,
                                "end" => $range_end_carbon->timestamp,
                            ], $booking);
                            $items[] = $this->composeFreeItem($range_start_carbon, $range_end_carbon, $booking_events);
                            
                            $to_break = false;
                            if($end_carbon->lte($start_booking_carbon)){
                                $range_start_carbon = $end_carbon->copy();
                                $range_end_carbon = $end_carbon->copy()->endOfDay();
                                $to_break = true;
                            }else if($end_carbon->lte($end_booking_carbon)){
                                $range_start_carbon = $start_booking_carbon->copy();
                                $range_end_carbon = $end_carbon->copy()->endOfDay();
                                $to_break = true;
                            }else{
                                $range_start_carbon = $start_booking_carbon->copy();
                                $range_end_carbon = $end_booking_carbon->copy();
                            }
                            
                            // Get bookings(events) and put them into items as events
                            $booking_events = $get_unique_booking_events_from_range([
                                "start" => $range_start_carbon->timestamp,
                                "end" => $range_end_carbon->timestamp,
                            ], $booking);
                            $put_events_into_items($booking_events);
                            
                            if(!empty($to_break) && $to_break === true)
                                break;
                            
                            $start_carbon = $end_booking_carbon->copy();
                        }
                    } // foreach::$merged_booking
                    
                    // If last item not free item and and last event less then end time of hall
                    // add left free time
                    if($start_carbon->lt($end_carbon) && count($items) > 0){
                        $last_item = $items[count($items) - 1];
                        if($last_item['type'] == 'event'){
                            list($start_last_item_carbon, $end_last_item_carbon) =
                                array_values($this->getStartEndCarbonFromBookingInstance($last_item));
                            if($end_last_item_carbon->lt($end_carbon))
                                $items[] = $this->composeFreeItem($start_carbon, $end_carbon);
                        }
                        if($last_item['type'] == 'free' && $itm['day'] > 4){
                            $date_str = implode('-', [$itm['year'], $itm['month'], $itm['day']]);
                            $start_last_item_carbon = \Carbon\Carbon::parse($date_str . ' ' . $last_item['from']);
                            $end_last_item_carbon = \Carbon\Carbon::parse($date_str . ' ' . $last_item['to']);
                            if($end_last_item_carbon->lt($end_carbon))
                                $items[] = $this->composeFreeItem($end_booking_carbon, $end_carbon);
                        }
                    }
                    
                // if exists only not approved events(bookings)
                }else{
                    $booking_events = $get_unique_booking_events_from_range([
                        "start" => $start_carbon->copy()->startOfDay()->timestamp,
                        "end" => $start_carbon->timestamp,
                    ], $booking);
                    $put_events_into_items($booking_events);
                    
                    $booking_events = $get_unique_booking_events_from_range([
                        "start" => $start_carbon->timestamp,
                        "end" => $end_carbon->timestamp,
                    ], $booking);
                    $items[] = $this->composeFreeItem($start_carbon, $end_carbon, $booking_events);
                    
                    $booking_events = $get_unique_booking_events_from_range([
                        "start" => $end_carbon->timestamp,
                        "end" => $start_carbon->copy()->endOfDay()->timestamp,
                    ], $booking);
                    $put_events_into_items($booking_events);
                }
                    
                if(!empty($items))
                    $itm['items'] = $items;
    
            }else{
                $itm['items'] = [$this->composeFreeItem($start_carbon, $end_carbon)];
            }
    
            $output[] = $itm;
            $start_date_carbon->addDays(1);
        }while(
            $start_date_carbon->lte($end_date_carbon) &&
            (
                empty($max_date_carbon) ||
                (!empty($max_date_carbon) && $start_date_carbon->lte($max_date_carbon))
            )
        );
    
        return $output;
    }
    
    protected function getStartEndCarbonFromBookingInstance($booking){
        if(!is_array($booking))
            $booking = $booking->toArray();
        $start_period_booking_carbon = \Carbon\Carbon::parse($booking['time']);
        $duration_period_booking = !empty($booking['custom_duration']) ?
            $booking['custom_duration'] : $booking['template_without_user_scope']['duration'];
        $end_period_booking_carbon = $start_period_booking_carbon->copy()
            ->add($duration_period_booking, 'minutes');
        return [
            'start_carbon' => $start_period_booking_carbon,
            'end_carbon' => $end_period_booking_carbon,
            'duration' => $duration_period_booking,
        ];
    }
    
    protected function getInnerEventsOfRange(array $range, array $events, $params = []){
        $inner_events = [];
        foreach($events as $k => $v){
            if((array_key_exists('type', $params) && $params['type'] === true && $v->approved == 0) ||
            (array_key_exists('type', $params) && $params['type'] === false && $v->approved == 1) ||
            (array_key_exists('exclude_ids', $params) && is_array($params['exclude_ids']) && in_array($v->id, $params['exclude_ids'])))
                continue;
            
            list($start_period_booking_carbon, $end_period_booking_carbon) =
                array_values($this->getStartEndCarbonFromBookingInstance($v));
                
            if(\Time::isPeriodInRangeByTimestamp(
                [
                    "start" => $start_period_booking_carbon->timestamp,
                    "end" => $end_period_booking_carbon->timestamp,
                ],
                [
                    "start" => $range['start'],
                    "end" => $range['end'],
                ]
            )){
                $inner_events[] = $v->toArray();
            }
        }
        
        return $inner_events;
    }
    
    protected function getEventsWithMergedCrossing($events){
        if(empty($events))
            return $events;
        
        // Get all essential columns for composing free data items
        $output = [];
        foreach ($events as $key => &$event) {
            if($event->approved == 0)
                continue;
            
            list($start_carbon, $end_carbon, $duration) =
                array_values($this->getStartEndCarbonFromBookingInstance($event));
            
            $output[] = [
                'id' => $event->id,
                'client_id' => $event->client_id,
                'time' => $event->time,
                'duration' => $duration,
                'approved' => $event->approved,
                'start_timestamp' => $start_carbon->timestamp,
                'end_timestamp' => $end_carbon->timestamp,
                'start' => $start_carbon->format('Y-m-d H:i:s'),
                'end' => $end_carbon->format('Y-m-d H:i:s'),
            ];
        }
        
        // Clean all crossing events
        $i = 0;
        do{
            if(!empty($crossing_exist)){
                foreach($output as $k => $v){
                    if($v['id'] == $crossing_exist)
                        unset($output[$k]);
                }
                $crossing_exist = false;
            }
            foreach($output as $key => &$out){
                foreach($output as $key_inner => $out_inner){
                    if($out['id'] == $out_inner['id'])
                        continue;
                    
                    if(
                        $crossing = \Time::isPeriodInRangeByTimestamp(
                            ['start' => $out_inner["start_timestamp"], 'end' => $out_inner["end_timestamp"]],
                            ['start' => $out["start_timestamp"], 'end' => $out["end_timestamp"]],
                            false
                        )
                    ){
                        if($crossing == 'all_out'){
                            $out["start_timestamp"] = $out_inner["start_timestamp"];
                            $out["end_timestamp"] = $out_inner["end_timestamp"];
                            $out["start"] = $out_inner["start"];
                            $out["end"] = $out_inner["end"];
                        }else if($crossing == 'only_start_in'){
                            $out["end_timestamp"] = $out_inner["end_timestamp"];
                            $out["end"] = $out_inner["end"];
                        }else if($crossing == 'only_end_in'){
                            $out["start_timestamp"] = $out_inner["start_timestamp"];
                            $out["start"] = $out_inner["start"];
                        }
                        
                        $crossing_exist = $out_inner['id'];
                        break 2;
                    }
                }
            }
        }while(!empty($crossing_exist) && $i < 1000);
        
        return $output;
    }
     
    protected function composeFreeItem($start_carbon, $end_carbon, $inner_events = null){
        $min_duration = \DurationRange::get(DurationRangeParams::START);
        $free_minutes = $this->carbonGetDiffBetweenTwoInstances($start_carbon, $end_carbon);
        
        $composed_free_item = [
            'type' => 'free',
            'from' => $start_carbon->format('H:i'),
            'to' => $end_carbon->format('H:i'),
            'free_minutes' => $free_minutes,
            'too_short' => $free_minutes < $min_duration,
        ];
        
        if(!empty($inner_events))
            $composed_free_item['not_approved_bookings'] = $inner_events;
            
        return $composed_free_item;
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
    
}