<?php

namespace App\Classes\BookedAndRequested;

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

class MainRetrieval{
    
    // Booking model
    protected $booking_model = null;
    // Range model
    protected $range = null;
    // User model
    protected $user = null;
    // Hall model
    protected $hall = null;
    // Worker model
    protected $worker = null;
    // Client model
    protected $client = null;
    // Template model
    protected $template = null;
    // Min datetime to retrieve bookings
    protected $past_ignore = false;
    
    function __construct(
        Range $range,
        $params = []
    ) {
        $this->range = $range;
        
        if(array_key_exists("past_ignore", $params) && is_bool($params["past_ignore"]))
            $this->past_ignore = $params["past_ignore"];
            
        // var_dump();
        // die();
        
        if(array_key_exists("user", $params) && is_a($params["user"], User::class))
            $this->user = $params["user"];
        
        if(array_key_exists("hall", $params) && is_a($params["hall"], Hall::class))
            $this->hall = $params["hall"];
        
        if(array_key_exists("worker", $params) && is_a($params["worker"], Worker::class))
            $this->worker = $params["worker"];
            
        if(array_key_exists("template", $params) && is_a($params["template"], Template::class))
            $this->template = $params["template"];
        
        if(array_key_exists("range", $params) && is_a($params["range"], Range::class))
            $this->range = $params["range"];
            
        if(array_key_exists("client", $params) && is_a($params["client"], Range::class))
            $this->client = $params["client"];
        
        $this->composeBookingModel();
    }
    
    protected function composeBookingModel(){
        $min_datetime_carbon = \Carbon\Carbon::parse($this->range->getNowDatetime());
        $start_datetime_carbon = \Carbon\Carbon::parse($this->range->getStartDatetime());
        $end_datetime_carbon = \Carbon\Carbon::parse($this->range->getEndDatetime());
        
        // var_dump($this->past_ignore);
        // die();
        
        if($this->past_ignore === true){
            if($end_datetime_carbon->lt($min_datetime_carbon)){
                $this->booking_model = null;
                return;
            }elseif($start_datetime_carbon->lt($min_datetime_carbon)){
                $start_datetime_carbon = clone $min_datetime_carbon;
            }
        }
        
        // var_dump($start_datetime_carbon->format('Y-m-d H:i:s'));
        // die();
        
        $this->booking_model = Booking::where('time', '>=', $start_datetime_carbon->format('Y-m-d H:i:s'))
            ->where('time', '<=', $end_datetime_carbon->format('Y-m-d H:i:s'));
        
        if(!empty($this->user))
            $this->booking_model->withoutGlobalScope(UserScope::class)
                ->byUser($this->user->id);
        
        if(!empty($this->hall))
            $this->booking_model->where('hall_id', '=', $this->hall->id);
        
        if(!empty($this->worker))
            $this->booking_model->where('worker_id', '=', $this->worker->id);
            
        if(!empty($this->template))
            $this->booking_model->where('template_id', '=', $this->template->id);
            
        if(!empty($this->client))
            $this->booking_model->where('client', '=', $this->client->id);
            
        $this->booking_model->with('templateWithoutUserScope.specific')->orderBy('time', 'ASC');
    }
    
    protected function getBookingsAsDateTimeKeyArray($only_approved = false){
        $booking = clone $this->booking_model;
        
        // var_dump(99999);
        // var_dump($booking->toSql());
        // die();
        
        if($only_approved)
            $booking->where('approved', '=', 1);
        $bookings = $booking->get();
        
        // var_dump(99999);
        // // var_dump($this->range);s
        // var_dump($booking->toSql());
        // die();
    
        if($bookings->isEmpty())
            return [];
    
        $booked_itms = [];
    
        foreach($bookings as $booking){
            $booking_date_index = \Carbon\Carbon::parse($booking['time'])->format('Y_m_d');
            $booking_hour_index = \Carbon\Carbon::parse($booking['time'])->format('H_i');
            $booked_itms[$booking_date_index][$booking_hour_index] = $booking;
        }
    
        return $booked_itms;
    }
    
}
