<?php

namespace App\Classes\Getter\Booking\Classes;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;

// use App\Classes\Getter\Template\Template;
use App\Models\Booking;
// use App\Classes\Getter\Booking\Enums\GetParams;
use App\Models\User;
use App\Scopes\UserScope;
use App\Classes\Range\Range;
// use App\Classes\Setting\Enums\Keys as SettingsKeys;
use App\Classes\Getter\Booking\Enums\Params;
use Illuminate\Support\Facades\DB;

// use App\Classes\Getter\Booking\Classes\MainBookingGetter;

class MainBookingGetter{
    
    protected $range, $owner, $id, $hall, $worker, $template, $client, $with;
    protected $past_ignore = false;
    protected $only_approved = false;
    protected $exclude_ids = [];
    protected $first_items = false;
    protected $exclude_range_start_and_end_dates = false;
    
    function __construct(
        User $owner,
        Range $range,
        array $params = []
    ) {
        $this->range = $range;
        $this->owner = $owner;
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
        
        // var_dump($params);
        // die();
        
        $this->first_items = !empty($params[Params::FIRST_ITEMS]) && $params[Params::FIRST_ITEMS] === true;
        
        $this->id = !empty($params[Params::ID]) && is_numeric($params[Params::ID]) ? (int)$params[Params::ID] : null;
        $this->hall = !empty($params[Params::HALL]) && is_numeric($params[Params::HALL]) ? (int)$params[Params::HALL] : null;
        $this->worker = !empty($params[Params::WORKER]) && is_numeric($params[Params::WORKER]) ? (int)$params[Params::WORKER] : null;
        $this->template = !empty($params[Params::TEMPLATE]) && is_numeric($params[Params::TEMPLATE]) ? (int)$params[Params::TEMPLATE] : null;
        $this->client = !empty($params[Params::CLIENT]) && is_numeric($params[Params::CLIENT]) ? (int)$params[Params::CLIENT] : null;
        
        $this->with = null;
        if(!empty($params[Params::WITH])){
            if(is_array($params[Params::WITH])){
                $this->with = $params[Params::WITH];
            }elseif(is_string($params[Params::WITH])){
                $this->with = [$params[Params::WITH]];
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
                    $carbon_from = \Carbon\Carbon::parse($item['from'], \Timezone::getCurrentTimezone());
                    $carbon_to = \Carbon\Carbon::parse($item['to'], \Timezone::getCurrentTimezone());
                    $item['duration'] = !empty($v->custom_duration) ? $v->custom_duration : $v->templateWithoutUserScope->duration;
                    // $from_time_carbon = \Carbon\Carbon::parse($item['time'], \Timezone::getCurrentTimezone());
                    // $item['time_from'] = $from_time_carbon->format('H:i');
                    // $item['time_to'] = $from_time_carbon->addSeconds($item['duration'])->format('H:i');
                    $item['time_crossing'] = false;
                    if(!empty($taken_times)){
                        foreach($taken_times as $taken_time){
                            if($carbon_from->between($taken_time["start"], $taken_time["end"], false)){
                                $item['time_crossing'] = true;
                                break;
                            }
                        }
                    }
                    $items[] = $item;
                    
                    $taken_times[] = [
                        "start" => $carbon_from,
                        "end" => $carbon_to,
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
        
        $time_comperison_sign_more = $this->exclude_range_start_and_end_dates === true ? '>' : '>=';
        $time_comperison_sign_less = $this->exclude_range_start_and_end_dates === true ? '<' : '<=';
            
        $booking_model = Booking::where(function($query) use (
            $start_datetime_carbon, $end_datetime_carbon,
            $time_comperison_sign_more, $time_comperison_sign_less
        ){
            $query->where([
                ['time', $time_comperison_sign_more, $start_datetime_carbon->format('Y-m-d H:i:s')],
                ['time', $time_comperison_sign_less, $end_datetime_carbon->format('Y-m-d H:i:s')]
            ])->orWhereHas('templateWithoutUserScope', function ($query) use (
                $start_datetime_carbon, $end_datetime_carbon,
                $time_comperison_sign_more, $time_comperison_sign_less
            ){
                // return $query->whereRaw(DB::raw('IFNULL(bookings.custom_duration, templates.duration) as right_duration'));
                // return $query->select(DB::raw('IFNULL(bookings.custom_duration, templates.duration) as right_duration'))
                    return $query->whereRaw(
                        DB::raw('
                            ADDTIME(
                                bookings.time,
                                SEC_TO_TIME(
                                    IFNULL(bookings.custom_duration, templates.duration) * 60
                                )
                            ) ' . $time_comperison_sign_more . ' "' . $start_datetime_carbon->format('Y-m-d H:i:s') . '"'
                        )
                    )
                    ->whereRaw(
                        DB::raw('
                            ADDTIME(
                                bookings.time,
                                SEC_TO_TIME(
                                    IFNULL(bookings.custom_duration, templates.duration) * 60
                                )
                            ) ' . $time_comperison_sign_less . ' "' . $end_datetime_carbon->format('Y-m-d H:i:s') . '"'
                        )
                    );
            });
        });
        
        // $booking_model->with(['templateWithoutUserScope' => function ($query) use ($start_datetime_carbon, $end_datetime_carbon) {
        //     // return $query->whereRaw(DB::raw('IFNULL(bookings.custom_duration, templates.duration) as right_duration'));
        //     // return $query->select(DB::raw('IFNULL(bookings.custom_duration, templates.duration) as right_duration'))
        //         return $query->whereRaw(
        //             DB::raw('
        //                 ADDTIME(
        //                     bookings.time,
        //                     SEC_TO_TIME(
        //                         IFNULL(bookings.custom_duration, templates.duration) * 60
        //                     )
        //                 ) >= "' . $start_datetime_carbon->format('Y-m-d H:i:s') . '"'
        //             )
        //         )
        //         ->whereRaw(
        //             DB::raw('
        //                 ADDTIME(
        //                     bookings.time,
        //                     SEC_TO_TIME(
        //                         IFNULL(bookings.custom_duration, templates.duration) * 60
        //                     )
        //                 ) <= "' . $end_datetime_carbon->format('Y-m-d H:i:s') . '"'
        //             )
        //         );
        // }]);
            
        // $booking_model->orWhereHas('templateWithoutUserScope', function ($query) use ($start_datetime_carbon, $end_datetime_carbon) {
        //     // return $query->whereRaw(DB::raw('IFNULL(bookings.custom_duration, templates.duration) as right_duration'));
        //     // return $query->select(DB::raw('IFNULL(bookings.custom_duration, templates.duration) as right_duration'))
        //         return $query->whereRaw(
        //             DB::raw('
        //                 ADDTIME(
        //                     bookings.time,
        //                     SEC_TO_TIME(
        //                         IFNULL(bookings.custom_duration, templates.duration) * 60
        //                     )
        //                 ) >= "' . $start_datetime_carbon->format('Y-m-d H:i:s') . '"'
        //             )
        //         )
        //         ->whereRaw(
        //             DB::raw('
        //                 ADDTIME(
        //                     bookings.time,
        //                     SEC_TO_TIME(
        //                         IFNULL(bookings.custom_duration, templates.duration) * 60
        //                     )
        //                 ) <= "' . $end_datetime_carbon->format('Y-m-d H:i:s') . '"'
        //             )
        //         );
        // });
        
        // $booking_model->select('bookings.*');
        // $booking_model->leftJoin('templates', 'bookings.template_id', '=', 'templates.id');
        // $booking_model->select(DB::raw('
        //     bookings.*,
        //     IFNULL(bookings.custom_duration, templates.duration) as right_duration
        // '));
        // $booking_model->with(["templateWithoutUserScope", "template" => function($q){
        //     // $q->select('template.IdUser', '=', 1);
        //     // $q->where('template.IdUser', '=', 1);
        //     $q->whereRaw(DB::raw('templateWithoutUserScope.duration > 0'));
        //     //$q->where('some other field', $someId);
        // }]);
        
        if(!empty($this->owner) && is_numeric($this->owner))
            $booking_model->withoutGlobalScope(UserScope::class)
                ->byUser($this->owner->id);
        
        if(!empty($this->hall) && is_numeric($this->hall))
            $booking_model->where('hall_id', '=', $this->hall);
        
        if(!empty($this->worker) && is_numeric($this->worker))
            $booking_model->where('worker_id', '=', $this->worker);
        
        if(!empty($this->template) && is_numeric($this->template))
            $booking_model->where('template_id', '=', $this->template);
        
        if(!empty($this->client) && is_numeric($this->client))
            $booking_model->where('client_id', '=', $this->client);
        
        // var_dump($this->with);
        // die();
        
        if(!empty($this->with) && is_array($this->with))
            foreach($this->with as $k => $v){
                if(is_string($v))
                    $booking_model->with($v);
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
    
    public function getBookingsAsDateTimeKeyArray(){
        $bookings = $this->composeBookingModel()->get();
        
        // var_dump($bookings->toArray());
        // die();
    
        if($bookings->isEmpty())
            return [];
    
        $booked_itms = [];
        foreach($bookings as $booking){
            $booking_date_index = \Carbon\Carbon::parse($booking->time)->format('Y_m_d');
            $booking_hour_index = \Carbon\Carbon::parse($booking->time)->format('H_i');
            $booked_itms[$booking_date_index][$booking_hour_index] = $booking;
        }
        
        // var_dump($booked_itms);
        // die();
    
        return $booked_itms;
    }
    
}