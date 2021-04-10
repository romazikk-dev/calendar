<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    protected $keyDateArray = null;
    
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
    
}
