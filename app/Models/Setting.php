<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserScope;

class Setting extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'key',
        'data',
    ];
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(){
        static::addGlobalScope(new UserScope);
    }
    
    public function scopeByKey($query, $key)
    {
        return $query->where('key', $key);
    }
    
    public function scopeByUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
}
