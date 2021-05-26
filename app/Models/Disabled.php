<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserScope;

// use App\Models\Disabled;

class Disabled extends Model
{
    use HasFactory;
    
    public function disable(){
        return $this->morphTo();
    }
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(){
        static::addGlobalScope(new UserScope);
        
        static::creating(function ($disabled) {
            $disabled->user_id = auth()->user()->id;
        });
    }
}
