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
    
    public function isFitsInTime(){
        $carbon_time = \Carbon\Carbon::parse($this->time);
        $holidays = \Holiday::getAllAsUniqueDateValue(
            $this->hallWithoutUserScope, $this->workerWithoutUserScope
        );
        
        // Check if date of `booking` is not in holidays
        $holiday_check_key = \Holiday::getKeyOfCarbonInstance($carbon_time);
        if(in_array($holiday_check_key, $holidays))
            return false;
        
        $duration = empty($this->custom_duration) ? $this->custom_duration : $this->right_duration;
        $carbon_iso_weekday = $carbon_time->isoWeekday();
        $weekday_str = array_values(Weekdays::all())[$carbon_iso_weekday - 1];
        $carbon_time_end = $carbon_time->copy()->addMinutes($duration);
            
        $hall_business_hours = $this->hallWithoutUserScope
            ->makeVisible(['business_hours'])
            ->toArray()['business_hours'];
        $hall_business_hours = json_decode($hall_business_hours, true)[$weekday_str];
        
        $carbon_start_hall = \Carbon\Carbon::parse($carbon_time->format("Y-m-d " . $hall_business_hours['start_hour'] . ':00'));
        $carbon_end_hall = \Carbon\Carbon::parse($carbon_time->format("Y-m-d " . $hall_business_hours['end_hour'] . ':00'));
        
        if($carbon_time->lt($carbon_start_hall) || $carbon_end_hall->lt($carbon_time_end))
            return false;
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
        
        return empty($bookings);
    }
    
    public function saveAsApproved(){
        if($this->approved != 1)
            $this->approved = 1;
        return $this->save();
    }
    
}
