<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;
    
    protected $fillable = ['from', 'to', 'description', 'title'];
    
    /**
     * Get the parent holidayable model
     */
    public function holidayable(){
        return $this->morphTo();
    }
}
