<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use App\Models\TemporaryMessage;

class TemporaryMessage extends Model
{
    use HasFactory;
    
    /**
     * Scope a query by key.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByKey($query, $key)
    {
        return $query->where('key', $key);
    }
    
    /**
     * Get the disabled.
     */
    public function disabled(){
        return $this->morphOne(Disabled::class, 'disable');
    }
}
