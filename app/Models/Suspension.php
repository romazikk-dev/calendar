<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suspension extends Model
{
    use HasFactory;
    
    /**
     * Get the parent suspensionable model
     */
    public function suspensionable()
    {
        return $this->morphTo();
    }
}
