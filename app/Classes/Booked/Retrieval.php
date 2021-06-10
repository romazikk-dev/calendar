<?php

namespace App\Classes\BookedAndRequested;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Booking;
// use App\Models\User;
// use App\Models\Hall;
// use App\Models\Template;
// use App\Models\Worker;
use App\Classes\Range\Range;
// use App\Exceptions\Api\Calendar\BadRangeException;

// use App\Classes\BookedAndRequested\Retrievial;

class Retrieval extends MainRetrieval{
    
    public function gett(){
        dd(33333);
    }
    
    public function get(){
        // var_dump(111);
        // die();
        
        dd(33333);
        
        return;
        
        $bookings = $this->getBookingsAsDateTimeKeyArray();
        
        $start_date_carbon = \Carbon\Carbon::parse($this->range->getStartDate());
        $end_date_carbon = \Carbon\Carbon::parse($this->range->getEndDate());
        
        $max_date_carbon = \Carbon\Carbon::parse($this->max_datetime_to_book);
        
        $output = [];
        do{
            //Set itm data related to hall
            // $hall_business_hours = $this->getHallBusinessHoursFromCarbonDate($start_date_carbon);
            
            $weekday = Range::getWeekdayFromCarbonInstance($start_date_carbon);
            // $bussiness_hours_of_weekday = $this->hall_business_hours[];
            
            // $bookable = true;
            // $is_weekend = false;
            $bookable = $start_date_carbon->lt($max_date_carbon) ? true : false;
            $datetime_of_item = $start_date_carbon->format("Y-m-d H:i:s");
            $holiday_val = $start_date_carbon->format("Y_m_d");
            if(
                $bookable === true &&
                (
                    \Suspension::isSuspendedOnDate($this->worker, $datetime_of_item) ||
                    // \Suspension::isSuspendedOnDate($this->hall, $datetime_of_item) ||
                    !empty($this->worker_holidays) && in_array($holiday_val, $this->worker_holidays)
                )
            )
                $bookable = false;
            
            $is_weekend = false;
            if(\Suspension::isSuspendedOnDate($this->hall, $datetime_of_item) ||
            (!empty($this->hall_holidays) && in_array($holiday_val, $this->hall_holidays)))
                $is_weekend = true;
            // $is_weekend = !empty($this->hall_holidays) && in_array($holiday_val, $this->hall_holidays) ? true : false;
            
            // var_dump($max_date_carbon->format("Y-m-d"));
            // die();
            
            //Set initial itm
            $itm = [
                'year' => $start_date_carbon->format("Y"),
                'month' => $start_date_carbon->format("m"),
                'day' => $start_date_carbon->format("d"),
                // 'is_weekend' => false,
                'is_weekend' => $is_weekend,
                'weekday' => $weekday,
                'bookable' => $bookable,
                // 'bookable' => false,
                'items' => null,
            ];
            
            $itm_date_index = $itm['year'] . '_' . $itm['month'] . '_' . $itm['day'];
            $itm_date = $itm['year'] . '-' . $itm['month'] . '-' . $itm['day'];
            
            //Set itm data related to hall
            $hall_business_hours = $this->getHallBusinessHoursFromCarbonDate($start_date_carbon);
            
            // var_dump($hall_business_hours->start_hour);
            // die();
            
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
            // if($hall_business_hours->is_weekend)
                
            
            $hall_start_time = $hall_business_hours->start_hour;
            $hall_end_time = $hall_business_hours->end_hour;
            $itm['items'] = [
                [
                    'type' => 'free',
                    'from' => $hall_start_time,
                    'to' => $hall_end_time,
                    'not_approved_bookings' => null
                ]
            ];
            
            if(!empty($bookings) && !empty($bookings[$itm_date_index])){
                $not_approved_bookings = [];
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
                
                // if($itm_date_index == '2021_04_16'){
                $first_item_key = array_key_first($booking);
                $first_item = $booking[$first_item_key];
                $first_item_start_carbon = \Carbon\Carbon::parse($first_item->time);
                
                // if($itm_date_index == '2021_04_22'){
                //     var_dump($start_carbon->lt($first_item_start_carbon));
                //     var_dump($first_item->approved);
                //     die();
                // }
                    
                if($start_carbon->lt($first_item_start_carbon) && !empty($first_item->approved)){
                    $items[] = [
                        'type' => 'free',
                        'from' => $start_carbon->format('H:i'),
                        'to' => $first_item_start_carbon->format('H:i'),
                        'not_approved_bookings' => null
                    ];
                    $start_carbon = $first_item_start_carbon;
                }
                
                foreach($booking as $k=>$v){
                    $booking_carbon = \Carbon\Carbon::parse($v->time);
                    $template_duration = $v->templateWithoutUserScope->duration + 900;
                    
                    // If booking not approved add it to $not_approved_bookings and continue to next iteration
                    if(!$v->approved){
                        if($this->isByClient($v->client_id))
                            $not_approved_bookings[$k] = [
                                'booking' => $v->toArray(),
                                'from' => $booking_carbon->format('H:i'),
                                'to' => $booking_carbon->addSeconds($template_duration)->format('H:i'),
                            ];
                    }else{
                        if(!empty($not_approved_bookings)){
                            $items[] = [
                                'type' => 'free',
                                'from' => $start_carbon->format('H:i'),
                                'to' => $booking_carbon->format('H:i'),
                                // 'from' => $start_carbon->format('H:i'),
                                // 'to' => $booking_carbon->format('H:i'),
                                'not_approved_bookings' => $not_approved_bookings
                            ];
                            
                            // Reset $not_approved_bookings for next iteration
                            $not_approved_bookings = [];
                        }else{
                            if($start_carbon->lt($booking_carbon)){
                                $items[] = [
                                    'type' => 'free',
                                    'from' => $start_carbon->format('H:i'),
                                    'to' => $booking_carbon->format('H:i'),
                                    'not_approved_bookings' => null
                                ];
                                $start_carbon = $booking_carbon;
                            }
                        }
                        
                        $booking_carbon->addSeconds($template_duration);
                        // if($this->isByClient())
                        if($this->isByClient($v->client_id))
                            $items[] = [
                                'type' => 'booked',
                                'from' => $booking_carbon->format('H:i'),
                                // 'to' => $booking_carbon->addSeconds($template_duration)->format('H:i'),
                                'to' => $booking_carbon->format('H:i'),
                                'booking' => $v->toArray(),
                            ];
                        
                        $start_carbon = $booking_carbon;
                        
                        // var_dump($start_carbon->format('H:i'));
                        // die();
                    }
                }
                
                if(!empty($not_approved_bookings)){
                    $items[] = [
                        'type' => 'free',
                        'from' => $start_carbon->format('H:i'),
                        'to' => $end_carbon->format('H:i'),
                        'not_approved_bookings' => $not_approved_bookings
                    ];
                    $start_carbon = $end_carbon;
                }
                
                // if($proccess_last_item){
                    if($start_carbon->lt($end_carbon)){
                        $items[] = [
                            'type' => 'free',
                            'from' => $start_carbon->format('H:i'),
                            'to' => $end_carbon->format('H:i'),
                            'not_approved_bookings' => !empty($not_approved_bookings) ? $not_approved_bookings : null
                        ];
                    }
                    
                    // else{
                    //     $last_item = array_pop($items);
                    //     $items[] = [
                    //         'type' => $last_item['type'],
                    //         'from' => $last_item['from'],
                    //         'to' => $end_carbon->format('H:i'),
                    //         'not_approved_bookings' => !empty($not_approved_bookings) ? $not_approved_bookings : null
                    //     ];
                    // }
                // }
                
                if(!empty($items))
                    $itm['items'] = $items;
                    
            }
            $output[] = $itm;
            $start_date_carbon->addDays(1);
        }while($start_date_carbon->lte($end_date_carbon));
        
        return $output;
    }
    
}
