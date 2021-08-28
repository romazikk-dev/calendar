<?php

namespace App\Classes\Getter\Booking\Classes;

use App\Models\Booking;
use App\Models\User;
use App\Scopes\UserScope;
use App\Classes\Range\Range;
use App\Classes\Getter\Booking\Enums\Params;
use Illuminate\Support\Facades\DB;
use App\Classes\Getter\Classes\ParameterChecker;
use App\Classes\Traits\Carbonable;

class MainBookingGetter{
    
    use Carbonable;
    
    protected $range, $owner, $id, $hall, $worker, $template, $client, $with, $status;
    protected $duration_start, $duration_end;
    protected $past_ignore = false;
    protected $only_approved = false;
    protected $exclude_ids = [];
    protected $first_items = false;
    protected $exclude_range_start_and_end_dates = false;
    protected $parameter_checker = null;
    
    function __construct(
        Range $range,
        array $params = []
    ) {
        $this->parameter_checker = new ParameterChecker();
        $this->range = $range;
        $this->parseParams($params);
    }
    
    private function parseParams(array $params){
        if(empty($params))
            return;
        
        $this->past_ignore = !empty($params[Params::PAST_IGNORE]) && $params[Params::PAST_IGNORE] === true;
        $this->only_approved = !empty($params[Params::ONLY_APPROVED]) && $params[Params::ONLY_APPROVED] === true;
        $this->exclude_ids = !empty($params[Params::EXCLUDE_IDS]) && is_array($params[Params::EXCLUDE_IDS]) ? $params[Params::EXCLUDE_IDS] : [];
        
        $this->exclude_range_start_and_end_dates =
            !empty($params[Params::EXCLUDE_RANGE_START_AND_END_DATES]) && $params[Params::EXCLUDE_RANGE_START_AND_END_DATES] === true;
        
        $this->first_items = !empty($params[Params::FIRST_ITEMS]) && $params[Params::FIRST_ITEMS] === true;
        
        foreach([
            ['key' => Params::OWNER, 'var' => 'owner'],
            ['key' => Params::ID, 'var' => 'id'],
            ['key' => Params::HALL, 'var' => 'hall'],
            ['key' => Params::WORKER, 'var' => 'worker'],
            ['key' => Params::TEMPLATE, 'var' => 'template'],
            ['key' => Params::CLIENT, 'var' => 'client']
        ] as $p){
            if(!empty($params[$p['key']])){
                if(is_numeric($params[$p['key']])){
                    $this->{$p['var']} = [(int)$params[$p['key']]];
                }elseif($this->parameter_checker->isArrayWithAllNumericValues($params[$p['key']])){
                    $this->{$p['var']} = $params[$p['key']];
                }else{
                    $this->{$p['var']} = [];
                }
            }else{
                $this->{$p['var']} = [];
            }
        }
        
        // $this->status = !empty($params[Params::STATUS]) && is_numeric($params[Params::STATUS]) ?
        //     (int)$params[Params::DURATION_START] : null;
        
        // Set duration `start` and `end`
        $this->duration_start = !empty($params[Params::DURATION_START]) && is_numeric($params[Params::DURATION_START]) ?
            (int)$params[Params::DURATION_START] : null;
            
        if(!empty($params[Params::DURATION_END]) && is_numeric($params[Params::DURATION_END])){
            $duration_end = (int)$params[Params::DURATION_END];
            if(empty($this->duration_start) ||
            (!empty($this->duration_start) && $this->duration_start <= $duration_end))
                $this->duration_end = $duration_end;
        }
        
        $this->with = null;
        foreach([
            ["key" => Params::WITH, "property" => "with"],
            ["key" => Params::STATUS, "property" => "status"]
        ] as $itm){
            if(!empty($params[$itm['key']])){
                if($this->parameter_checker->isArrayWithAllStrValues($params[$itm['key']])){
                    $this->{$itm['property']} = $params[$itm['key']];
                }elseif(is_string($params[$itm['key']])){
                    $this->{$itm['property']} = [$params[$itm['key']]];
                }
            }
        }
    }
    
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
                'type' => 'events',
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
                $taken_times = [];
                foreach($bookings[$itm_date_index] as $k => $v){
                    // $v_carbon = \Carbon\Carbon::parse($v->time);
                    // $v_duration = $v->templateWithoutUserScope->duration;
                    $item = $v->toArray();
                    // $carbon_from = \Carbon\Carbon::parse($item['from'], \Timezone::getCurrentTimezone());
                    // $carbon_to = \Carbon\Carbon::parse($item['to'], \Timezone::getCurrentTimezone());
                    $carbon_from = \Carbon\Carbon::parse($item['time'], \Timezone::getCurrentTimezone());
                    // $carbon_to = \Carbon\Carbon::parse($item['to'], \Timezone::getCurrentTimezone());
                    $item['duration'] = !empty($v->custom_duration) ? $v->custom_duration : $v->templateWithoutUserScope->duration;
                    $carbon_to = $carbon_from->copy()->add($item['duration'], 'minutes');
                    
                    // var_dump($carbon_from->timestamp);
                    // var_dump($carbon_to->timestamp);
                    // die();
                    
                    // $from_time_carbon = \Carbon\Carbon::parse($item['time'], \Timezone::getCurrentTimezone());
                    // $item['time_from'] = $from_time_carbon->format('H:i');
                    // $item['time_to'] = $from_time_carbon->addSeconds($item['duration'])->format('H:i');
                    $item['time_crossing'] = false;
                    if(!empty($taken_times)){
                        foreach($taken_times as $taken_time){
                            if(
                                // $carbon_from->between($taken_time["start"], $taken_time["end"], false) &&
                                \Time::isPeriodInRangeByTimestamp(
                                    ['start' => $carbon_from->timestamp, 'end' => $carbon_to->timestamp],
                                    ['start' => $taken_time["start"]->timestamp, 'end' => $taken_time["end"]->timestamp],
                                ) &&
                                $taken_time["worker_id"] == $item['worker_id']
                            ){
                                if(
                                    ($taken_time["approved"] == 1 && $item['approved'] == 1) ||
                                    ($taken_time["approved"] == 0 && $item['approved'] == 0) ||
                                    ($taken_time["approved"] == 0 && $item['approved'] == 1)
                                )
                                    $items[$taken_time["item_index"]]['time_crossing'] = true;
                                
                                if(
                                    ($taken_time["approved"] == 1 && $item['approved'] == 1) ||
                                    ($taken_time["approved"] == 0 && $item['approved'] == 0) ||
                                    ($taken_time["approved"] == 1 && $item['approved'] == 0)
                                )
                                    $item['time_crossing'] = true;
                                
                                break;
                            }
                        }
                    }
                    $items[] = $item;
                    
                    $taken_times[] = [
                        "start" => $carbon_from,
                        "end" => $carbon_to,
                        "worker_id" => $item['worker_id'],
                        "item_index" => count($items) - 1,
                        "approved" => $item['approved'],
                    ];
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
        
        if($this->first_items === true && !empty($output) && !empty($output[0]))
            return !empty($output[0]['items']) ? $output[0]['items'] : null;
        return $output;
    }
    
    protected function composeBookingModel(){
        $start_datetime_carbon = \Carbon\Carbon::parse($this->range->getStartDatetime());
        $end_datetime_carbon = \Carbon\Carbon::parse($this->range->getEndDatetime());
        
        if(!empty($this->past_ignore) && $this->past_ignore === true){
            $min_datetime_carbon = \Carbon\Carbon::parse($this->range->getNowDatetime());
            if($end_datetime_carbon->lt($min_datetime_carbon)){
                // $this->booking_model = null;
                return null;
            }elseif($start_datetime_carbon->lt($min_datetime_carbon)){
                $start_datetime_carbon = clone $min_datetime_carbon;
            }
        }
        
        // $booking_model = Booking::where('time', '>=', $start_datetime_carbon->format('Y-m-d H:i:s'))
        //     ->where('time', '<=', $end_datetime_carbon->format('Y-m-d H:i:s'));
        
        // $exclude_range_start_and_end_dates
        
        // $time_comperison_sign_more = $this->exclude_range_start_and_end_dates === true ? '>' : '>=';
        $time_comperison_sign_less = $this->exclude_range_start_and_end_dates === true ? '<' : '<=';
        // $time_comperison_sign_more, $time_comperison_sign_less
        
        $duration = [
            'start' => $this->duration_start,
            'end' => $this->duration_end,
        ];
        
        // var_dump($this->range->getStartDatetime());
        // var_dump($this->range->getEndDatetime());
        // var_dump($start_datetime_carbon->toDate());
        // var_dump($end_datetime_carbon->toDate());
        // die();
        
        /*
        *   Starts BOOKING modal with setting date range of retrieving bookings(events)
        *   will be returned all events which starts or ends in a range of dates
        *   will add template_without_user_scope to bookings which custom_duration is null
        *   even if parameter(with) to get it(template_without_user_scope) not specified
        */
        $booking_model = Booking::where(function($query) use (
            $start_datetime_carbon, $end_datetime_carbon, $time_comperison_sign_less, $duration
        ){
            
            $start_datetime = $start_datetime_carbon->format('Y-m-d H:i:s');
            $end_datetime = $end_datetime_carbon->format('Y-m-d H:i:s');
            
            $query->whereHas('templateWithoutUserScope', function ($query) use (
                $start_datetime, $end_datetime, $time_comperison_sign_less, $duration
            ){
                $query->whereRaw(
                    DB::raw('
                        bookings.time <= "' . $start_datetime . '" AND
                        "' . $start_datetime . '" ' . $time_comperison_sign_less . ' ' .
                        'ADDTIME(
                            bookings.time,
                            SEC_TO_TIME(
                                IFNULL(bookings.custom_duration, templates.duration) * 60
                            )
                        )'
                    )
                )
                ->orWhereRaw(
                    DB::raw('
                        bookings.time ' . $time_comperison_sign_less . ' "' . $end_datetime . '" AND
                        "' . $end_datetime . '" <= ' .
                        'ADDTIME(
                            bookings.time,
                            SEC_TO_TIME(
                                IFNULL(bookings.custom_duration, templates.duration) * 60
                            )
                        )'
                    )
                )
                ->orWhereRaw(
                    DB::raw('
                        "' . $start_datetime . '" < bookings.time AND
                        "' . $end_datetime . '" > ' .
                        'ADDTIME(
                            bookings.time,
                            SEC_TO_TIME(
                                IFNULL(bookings.custom_duration, templates.duration) * 60
                            )
                        )'
                    )
                );
                
                if(!empty($duration['start']))
                    $query->whereRaw(DB::raw(
                        'IFNULL(bookings.custom_duration, templates.duration) >= ' . $duration['start']
                    ));
                
                if(!empty($duration['end']))
                    $query->whereRaw(DB::raw(
                        'IFNULL(bookings.custom_duration, templates.duration) <= ' . $duration['end']
                    ));
            });
        });
        
        if(!empty($this->owner) && is_array($this->owner)){
            $booking_model->withoutGlobalScope(UserScope::class)
                ->byUsers($this->owner);
        }
        
        if(!empty($this->hall) && is_array($this->hall))
            $booking_model->whereIn('hall_id', $this->hall);
        
        if(!empty($this->worker) && is_array($this->worker))
            $booking_model->whereIn('worker_id', $this->worker);
        
        if(!empty($this->template) && is_array($this->template))
            $booking_model->whereIn('template_id', $this->template);
        
        if(!empty($this->client) && is_array($this->client))
            $booking_model->whereIn('client_id', $this->client);
        
        // var_dump(111);
        // var_dump($this->client);
        // die();
        
        if(!empty($this->with) && is_array($this->with))
            foreach($this->with as $k => $v){
                if(is_string($v))
                    $booking_model->with($v);
            }
        
        // $this->parameter_checker->isArrayWithAllStrValues($params[$itm['key']])
        // $booking_model->with('templateWithoutUserScope');
            
        if(!empty($this->status) && is_array($this->status)){
            if(count($this->status) > 1){
                for($i = 0; $i < count($this->status); $i++){
                    if($i == 0){
                        $booking_model->where('approved', $this->status[$i] == 'approved' ? 1 : 0);
                    }else{
                        $booking_model->where('approved', $this->status[$i] == 'approved' ? 1 : 0);
                    }
                }
            }else{
                $booking_model->where('approved', $this->status[0] == 'approved' ? 1 : 0);
            }
            // if(in_array("approved", $this->status))
            // foreach($this->status as $k => $v){
            //     if($v == 'approved')
            //         $booking_model->where('approved', 1);
            //     if($v == 'approved')
            //         $booking_model->where('approved', 1);
            // }
        }
            
        if(!empty($this->only_approved) && $this->only_approved === true)
            $booking_model->where('approved', '=', 1);
        
        if(!empty($this->exclude_ids))
            $booking_model->whereNotIn('id', $this->exclude_ids);
            
        $booking_model->orderBy('time', 'ASC');
        
        // var_dump($booking_model->get()->toArray());
        // die();
        
        // $sql = $booking_model->toSql();
        // $sql = \Str::replaceArray('?', $booking_model->getBindings(), $booking_model->toSql());
        // var_dump($sql);
        // var_dump($booking_model->toSql());
        // die();
        
        return $booking_model;
    }
    
    protected function isWithInArray(string $with_str){
        if(!is_array($this->with) || empty($this->with))
            return false;
            
        foreach($this->with as $k => $v){
            if(str_starts_with($v, $with_str))
                return true;
        }
        
        return false;
    }
    
    public function cleanBookingsAfterRetrieving($bookings){
        // var_dump($this->with);
        // die();
        // if(
        //     empty($this->with) ||
        //     (
        //         is_array($this->with) && !empty($this->with) && !in_array('templateWithoutUserScope', $this->with)
        //     )
        // ){
        if(!$this->isWithInArray('templateWithoutUserScope')){
            // var_dump(111);
            // die();
            foreach($bookings as $k => &$v)
                $v->makeHidden('templateWithoutUserScope');
        }
    
        return $bookings;
    }
    
    public function getBookingsAsDateTimeKeyArray(bool $as_arr = false){
        $bookings = $this->composeBookingModel()->get();
        $bookings = $this->cleanBookingsAfterRetrieving($bookings);
        // $booking_model = $this->composeBookingModel();
        // $sql = $booking_model->toSql();
        // $sql = \Str::replaceArray('?', $booking_model->getBindings(), $booking_model->toSql());
        // var_dump($sql);
        // var_dump($bookings->toArray());
        // var_dump($bookings);
        // die();
    
        if($bookings->isEmpty())
            return [];
    
        $booked_itms = [];
        foreach($bookings as $booking){
            $booking_date_index = \Carbon\Carbon::parse($booking->time)->format('Y_m_d');
            $booking_hour_index = \Carbon\Carbon::parse($booking->time)->format('H_i');
            $booking_hour_index .= '_' . $booking->worker_id;
            $booking_hour_index .= '_' . $booking->id;
            if($as_arr === true){
                $booked_itms[$booking_date_index][$booking_hour_index] = $booking->toArray();
            }else{
                $booked_itms[$booking_date_index][$booking_hour_index] = $booking;
            }
        }
        
        // var_dump($booked_itms);
        // die();
    
        return $booked_itms;
    }
    
}