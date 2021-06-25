<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserScope;

class Booking extends Model
{
    use HasFactory;
    
    protected $keyDateArray = null;
    
    protected $guarded = [];
    protected $appends = ['from','to','date'];
    
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
    
    public function getToAttribute()
    {
        return \Carbon\Carbon::parse($this->time)->addSeconds(
            $this->templateWithoutUserScope->duration
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
    
}
