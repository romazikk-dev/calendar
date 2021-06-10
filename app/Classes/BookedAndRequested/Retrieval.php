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
    
    public function get(){
        $bookings = $this->getBookingsAsDateTimeKeyArray();
        
        $start_date_carbon = \Carbon\Carbon::parse($this->range->getStartDate());
        $end_date_carbon = \Carbon\Carbon::parse($this->range->getEndDate());
        
        $output = [];
        do{
            $weekday = Range::getWeekdayFromCarbonInstance($start_date_carbon);
            
            //Set initial itm
            $itm = [
                'year' => $start_date_carbon->format("Y"),
                'month' => $start_date_carbon->format("m"),
                'day' => $start_date_carbon->format("d"),
                'weekday' => $weekday,
                'count_total' => 0,
                'count_approved' => 0,
                'count_not_approved' => 0,
                'items' => null,
            ];
            
            $itm_date_index = $itm['year'] . '_' . $itm['month'] . '_' . $itm['day'];
            $itm_date = $itm['year'] . '-' . $itm['month'] . '-' . $itm['day'];
            
            if(!empty($bookings) && !empty($bookings[$itm_date_index])){
                $items = [];
                $count_total_items = 0;
                $count_approved_items = 0;
                $count_not_approved_items = 0;
                foreach($bookings[$itm_date_index] as $k => $v){
                    // $v_carbon = \Carbon\Carbon::parse($v->time);
                    // $v_duration = $v->templateWithoutUserScope->duration;
                    $item = $v->toArray();
                    $item['duration'] = $v->templateWithoutUserScope->duration;
                    $from_time_carbon = \Carbon\Carbon::parse($item['time'], \Timezone::getCurrentTimezone());
                    $item['time_from'] = $from_time_carbon->format('H:i');
                    $item['time_to'] = $from_time_carbon->addSeconds($item['duration'])->format('H:i');
                    $items[] = $item;
                    $count_total_items++;
                    if(!empty($item['approved'])){
                        $count_approved_items++;
                    }else{
                        $count_not_approved_items++;
                    }
                }
                
                $itm['count_total'] = $count_total_items;
                $itm['count_approved'] = $count_approved_items;
                $itm['count_not_approved'] = $count_not_approved_items;
                
                if(!empty($items))
                    $itm['items'] = $items;
                    
            }
            $output[] = $itm;
            $start_date_carbon->addDays(1);
        }while($start_date_carbon->lte($end_date_carbon));
        
        return $output;
    }
    
}
