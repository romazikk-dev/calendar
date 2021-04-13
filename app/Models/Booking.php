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
    
    public function getFreeSlots($year, $month, $day){
        if(is_null($this->keyDateArray)){
            $this->keyDateArray = [];
            foreach($this->toArray() as $itm){
                $time = \Carbon\Carbon::parse($this->time);
                dd($time);
                // $this->keyDateArray[]
                // $start_date_carbon = \Carbon\Carbon::parse($start_date);
                // $end_date_carbon = \Carbon\Carbon::parse($end_date);
            }
        }
    }
    
    /**
     * Scope a query to only one user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
    
    public function template(){
        return $this->belongsTo(Template::class, 'template_id', 'id');
        // return $this->belongsToMany('App\Models\Worker');
    }
    
    public function templateWithoutUserScope(){
        return $this->belongsTo(Template::class, 'template_id', 'id')->withoutGlobalScope(UserScope::class);
        // return $this->belongsTo('App\Models\Template');
    }
    
}
