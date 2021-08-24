<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserScope;
use App\Classes\Range\Range;
use App\Classes\Getter\Booking\Enums\Params as BookingGetterParams;
use App\Classes\Enums\Weekdays;

class Booking extends Model
{
    use HasFactory;
    
    protected $keyDateArray = null;
    
    protected $guarded = [];
    protected $appends = ['from','to','date','right_duration'];
    
    protected $not_fits_in_time_reason = null;
    
    // public function getFreeSlots($year, $month, $day){
    //     if(is_null($this->keyDateArray)){
    //         $this->keyDateArray = [];
    //         foreach($this->toArray() as $itm){
    //             $time = \Carbon\Carbon::parse($this->time);
    //             dd($time);
    //             // $this->keyDateArray[]
    //             // $start_date_carbon = \Carbon\Carbon::parse($start_date);
    //             // $end_date_carbon = \Carbon\Carbon::parse($end_date);
    //         }
    //     }
    // }
    
    public function getDateAttribute()
    {
        return \Carbon\Carbon::parse($this->time)->format('Y-m-d');
    }
    
    public function getFromAttribute()
    {
        return \Carbon\Carbon::parse($this->time)->format('H:i');
    }
    
    public function getRightDurationAttribute()
    {
        return $this->custom_duration ?? $this->templateWithoutUserScope->duration;
    }
    
    public function getToAttribute()
    {
        // var_dump(\Carbon\Carbon::parse($this->time, \Timezone::getCurrentTimezone())->addMinutes(
        //     $this->custom_duration ?? $this->templateWithoutUserScope->duration
        // )->format('H:i'));
        // die();
        
        return \Carbon\Carbon::parse($this->time, \Timezone::getCurrentTimezone())->addMinutes(
            $this->custom_duration ?? $this->templateWithoutUserScope->duration
        )->format('H:i');
    }
    
    /**
     * Scope a query to only one user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function scopeUser($query, $user_id)
    // {
    //     return $query->where('user_id', $user_id);
    // }
    
    // public function template(){
    //     return $this->belongsTo(Template::class, 'template_id', 'id');
    //     // return $this->belongsToMany('App\Models\Worker');
    // }
    
    public function template(){
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }
    
    public function templateWithoutUserScope(){
        return $this->belongsTo(Template::class, 'template_id', 'id')->withoutGlobalScope(UserScope::class);
    }
    
    public function worker(){
        return $this->belongsTo(Worker::class, 'worker_id', 'id');
    }
    
    public function workerWithoutUserScope(){
        return $this->belongsTo(Worker::class, 'worker_id', 'id')->withoutGlobalScope(UserScope::class);
    }
    
    public function hall(){
        return $this->belongsTo(Hall::class, 'hall_id', 'id');
    }
    
    public function hallWithoutUserScope(){
        return $this->belongsTo(Hall::class, 'hall_id', 'id')->withoutGlobalScope(UserScope::class);
    }
    
    public function client(){
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
    
    public function clientWithoutUserScope(){
        return $this->belongsTo(Client::class, 'client_id', 'id')->withoutGlobalScope(UserScope::class);
    }
    
    /**
     * Scope a query to only include bookings of given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
    
    /**
     * Scope a query to only include bookings of given users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $user_ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUsers($query, $user_ids)
    {
        return $query->whereIn('user_id', $user_ids);
    }
    
    /**
     * Scope a query to only include bookings of given id.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeById($query, $id)
    {
        return $query->where('id', $id);
    }
    
    /**
     * Scope a query to only include bookings of given client.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByClient($query, $client_id)
    {
        return $query->where('client_id', $client_id);
    }
    
    /**
     * Scope a query to only include only approved bookings.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', 1);
    }
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(){
        static::addGlobalScope(new UserScope);
        // static::addGlobalScope(new NotDeletedScope);
    }
    
    public function isFitsInTime(array $check = []){
        // $admins_booking_calendar_main_settings = \Setting::of(SettingKeys::ADMINS_BOOKING_CALENDAR_MAIN)->getOrPlaceholder();
        
        $carbon_time = \Carbon\Carbon::parse($this->time);
        $datetime = $carbon_time->format("Y-m-d H:i:s");
        
        $is_check_param = function(string $param) use ($check){
            return empty($check) || (!empty($check) && in_array($param, $check));
        };
        
        if($is_check_param('hall_suspension') &&
        \Suspension::isSuspendedOnDate($this->hallWithoutUserScope, $datetime)){
            $this->not_fits_in_time_reason = 'Hall is suspended on ' . $datetime;
            return false;
        }
        
        if($is_check_param('worker_suspension') &&
        \Suspension::isSuspendedOnDate($this->workerWithoutUserScope, $datetime)){
            $this->not_fits_in_time_reason = 'Worker is suspended on ' . $datetime;
            return false;
        }
        
        if($is_check_param('hall_holidays') || $is_check_param('worker_holidays') || $is_check_param('all_holidays')){
            // $carbon_time = \Carbon\Carbon::parse($this->time);
            $holidays = \Holiday::getAllAsUniqueDateValue(
                $this->hallWithoutUserScope, $this->workerWithoutUserScope, [
                    "separate" => true,
                ]
            );
            
            // Check if date of `booking` is not in holidays
            $holiday_check_key = \Holiday::getKeyOfCarbonInstance($carbon_time);
            
            if($is_check_param('hall_holidays') || $is_check_param('all_holidays')){
                if(in_array($holiday_check_key, $holidays['hall_holidays'])){
                    $this->not_fits_in_time_reason = 'You trying book on date `' . $datetime . '` wich is holiday for hall right now.';
                    return false;
                }
            }
            
            if($is_check_param('worker_holidays') || $is_check_param('all_holidays')){
                if(in_array($holiday_check_key, $holidays['worker_holidays'])){
                    $this->not_fits_in_time_reason = 'You trying book on date `' . $datetime . '` wich is holiday for worker right now.';
                    return false;
                }
            }
        }
        
        $duration = empty($this->custom_duration) ? $this->custom_duration : $this->right_duration;
        $carbon_iso_weekday = $carbon_time->isoWeekday();
        $weekday_str = array_values(Weekdays::all())[$carbon_iso_weekday - 1];
        $carbon_time_end = $carbon_time->copy()->addMinutes($duration);
        
        if($is_check_param('business_hours')){
            $hall_business_hours = $this->hallWithoutUserScope
                ->makeVisible(['business_hours'])
                ->toArray()['business_hours'];
            
            // var_dump($hall_business_hours);
            // die();
                
            $hall_business_hours = json_decode($hall_business_hours, true)[$weekday_str];
            if(!empty($hall_business_hours['is_weekend']) && $hall_business_hours['is_weekend'] == 'on'){
                $this->not_fits_in_time_reason = 'You trying book on date `' . $datetime . '` wich is weekend for hall right now(bussiness hour is weekend).';
                return false;
            }
            // var_dump($hall_business_hours);
            // die();
            
            $carbon_start_hall = \Carbon\Carbon::parse($carbon_time->format("Y-m-d " . $hall_business_hours['start_hour'] . ':00'));
            $carbon_end_hall = \Carbon\Carbon::parse($carbon_time->format("Y-m-d " . $hall_business_hours['end_hour'] . ':00'));
            
            if($carbon_time->lt($carbon_start_hall) || $carbon_end_hall->lt($carbon_time_end)){
                $this->not_fits_in_time_reason = 'You trying book on date `' . $datetime . '` wich is out of range working hours of hall right now(not in range of bussiness hours).';
                return false;
            }
        }
        
        
        // Weekdays
        // var_dump($carbon_iso_weekday);
        // var_dump($weekday_str);
        // var_dump($carbon_time->lt($carbon_start_hall));
        // var_dump($carbon_start_hall);
        // var_dump($hall_business_hours);
        // die();
        
        // var_dump($holidays);
        // die();
        
        // $duration = empty($this->custom_duration) ? $this->custom_duration : $this->right_duration;
        
        // var_dump($this->hallWithoutUserScope->toArray());
        // die();
        
        if($is_check_param('crossing_other_bookings')){
            $start = $this->time;
            $end = $carbon_time_end->format('Y-m-d H:i:s');
            
            $bookings = \Getter::bookings()->all(
                new Range($start, $end, 'month'), [
                    BookingGetterParams::ONLY_APPROVED => true,
                    BookingGetterParams::EXCLUDE_IDS => [$this->id],
                    BookingGetterParams::FIRST_ITEMS => true,
                    BookingGetterParams::EXCLUDE_RANGE_START_AND_END_DATES => true,
                    BookingGetterParams::WORKER => $this->worker_id
                ]
            );
            
            // var_dump($bookings);
            // die();
            if(!empty($bookings)){
                $this->not_fits_in_time_reason = 'You trying book on date `' . $datetime . '` wich is already taken by another event(booking).';
                return false;
            }
        }
        
        // return empty($bookings);
        return true;
    }
    
    public function getNotFitsInTimeReason(){
        return $this->not_fits_in_time_reason;
    }
    
    public function saveAsApproved(){
        if($this->approved != 1)
            $this->approved = 1;
        return $this->save();
    }
    
}
