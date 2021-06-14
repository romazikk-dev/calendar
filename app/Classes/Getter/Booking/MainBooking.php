<?php

namespace App\Classes\Getter\Booking;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;

// use App\Classes\Getter\Template\Template;
use App\Models\Booking;
use App\Classes\Getter\Booking\Enums\GetParams;
use App\Models\User;
use App\Scopes\UserScope;
use App\Classes\Range\Range;
use App\Classes\Setting\Enums\Keys as SettingsKeys;

class MainBooking{
    
    // function __construct(
    //     // User $user,
    //     // Hall $hall,
    //     // Worker $worker,
    //     // Template $template,
    //     // Range $range,
    //     // Client $client = null
    // ) {
    //     // parent::__construct();
    //     // $this->user = $user;
    //     // $this->hall = $hall;
    //     // $this->worker = $worker;
    //     // $this->template = $template;
    //     // $this->range = $range;
    // 
    //     $this->setMaxDatesToBook();
    //     $this->setHolidays();
    // 
    //     if(!is_null($client))
    //         $this->client = $client;
    // 
    //     // $this->setBusinessHours();
    // 
    //     // var_dump($this->hall_business_hours);
    //     // die();
    // 
    //     $this->hall_business_hours = json_decode($this->hall->business_hours);
    // 
    //     // var_dump($this->hall_business_hours);
    //     // die();
    // 
    //     // $this->hall_business_hours = json_decode($this->hall->business_hours, true);
    //     // $this->hall_business_hours = \Setting::of(SettingsKeys::HALL_DEFAULT_BUSINESS_HOURS)->arrange($this->hall->business_hours);
    // 
    //     $this->composeBookingModel();
    // }
    
    private function getMaxDatesToBook(integer $owner){
        $setting = \Setting::of(SettingsKeys::CLIENTS_BOOKING_CALENDAR_MAIN)->getOrPlaceholder([
            'user_id' => $owner,
        ]);
        
        // var_dump($setting);
        // die();
        
        $max_future_booking_offset = $setting['max_future_booking_offset'];
        $max_future_booking_offset_seconds = (60 * 60 * 24) * $max_future_booking_offset;
        $max_timestamp = time() + $max_future_booking_offset_seconds;
        $max_datetime = date('Y-m-d 23:59:59', $max_timestamp);
        
        return [
            'max_timestamp_to_book' => strtotime($max_datetime),
            'max_date_to_book' => trim(explode(' ', $max_datetime)[0]),
            'max_datetime_to_book' => $max_datetime,
        ];
        // $this->max_timestamp_to_book = strtotime($max_datetime);
        // $this->max_date_to_book = trim(explode(' ', $max_datetime)[0]);
        // $this->max_datetime_to_book = $max_datetime;
    }
    
    protected function getComposedBookingModel(Range $range, array $params = []){
        $start_datetime_carbon = \Carbon\Carbon::parse($range->getStartDatetime());
        $end_datetime_carbon = \Carbon\Carbon::parse($range->getEndDatetime());
        
        if(!empty($params['past_ignore'])){
            $min_datetime_carbon = \Carbon\Carbon::parse($range->getNowDatetime());
            if($end_datetime_carbon->lt($min_datetime_carbon)){
                return null;
            }elseif($start_datetime_carbon->lt($min_datetime_carbon)){
                $start_datetime_carbon = clone $min_datetime_carbon;
            }
        }
        
        $booking_model = Booking::where('time', '>=', $start_datetime_carbon->format('Y-m-d H:i:s'))
            ->where('time', '<=', $end_datetime_carbon->format('Y-m-d H:i:s'));
        
        if(!empty($params['user']) && is_numeric($params['user']))
            $booking_model->withoutGlobalScope(UserScope::class)
                ->byUser($params['user']);
        
        if(!empty($params['hall']) && is_numeric($params['user_id']))
            $booking_model->where('hall', '=', $params['hall']);
        
        if(!empty($params['worker']) && is_numeric($params['worker']))
            $booking_model->where('worker_id', '=', $params['worker']);
        
        if(!empty($params['template']) && is_numeric($params['template']))
            $booking_model->where('template_id', '=', $params['template']);
        
        if(!empty($params['client']) && is_numeric($params['client']))
            $booking_model->where('client', '=', $params['client']);
            
        $booking_model->with('templateWithoutUserScope.specific')->orderBy('time', 'ASC');
        
        return $booking_model;
    }
    
    protected function getBookingsAsDateTimeKeyArray(Range $range, array $params = []){
        $booking_model = $this->getComposedBookingModel($range, $params);
        
        if(!empty($params['only_approved']) && $params['only_approved'] === true)
            $booking_model->where('approved', '=', 1);
        $bookings = $booking_model->get();
    
        if($bookings->isEmpty())
            return [];
    
        $booked_itms = [];
    
        foreach($bookings as $booking){
            $booking_date_index = \Carbon\Carbon::parse($booking->time)->format('Y_m_d');
            $booking_hour_index = \Carbon\Carbon::parse($booking->time)->format('H_i');
            $booked_itms[$booking_date_index][$booking_hour_index] = $booking;
        }
    
        return $booked_itms;
    }
    
}