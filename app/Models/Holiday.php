<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use App\Models\Holiday;

class Holiday extends Model
{
    use HasFactory;
    
    protected $fillable = ['from', 'to', 'description', 'title', 'timestamp'];
    
    /**
     * Get the parent holidayable model
     */
    public function holidayable(){
        return $this->morphTo();
    }
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->user_id = auth()->user()->id;
        });
    }

}
