<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserScope;
use App\Scopes\NotDeletedScope;

class Hall extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function workers(){
        return $this->belongsToMany('App\Models\Worker');
        // return $this->belongsToMany('App\Models\Worker');
    }
    
    public function workersWithoutGlobalScope(){
        return $this->belongsToMany('App\Models\Worker')->withoutGlobalScope(UserScope::class);
    }
    
    /**
     * The labels for attributes.
     *
     * @var array
     */
    public static $labels = [
        'id' => 'ID',
        'title' => 'Title',
        'description' => 'Description',
        'short_description' => 'Short description',
        'notice' => 'Notice',
        'country' => 'Country',
        'town' => 'Town',
        'street' => 'Street',
        'is_closed' => 'Closed',
        'is_deleted' => 'Deleted',
        'business_hours' => 'Business hours',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At'
    ];
    
    public function toLabelArray(){
        $arr = [];
        foreach($this->toArray() as $k => $v){
            $arr[$k] = [
                'label' => self::$labels[$k],
                'value' => $v,
            ];
        }
        return $arr;
    }
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'business_hours',
        'is_deleted',
    ];
    
    /**
     * Scope a query to only include not deleted HALLS.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function scopeNotDeleted($query)
    // {
    //     return $query->where('is_deleted', '=', 0);
    // }
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(){
        static::addGlobalScope(new UserScope);
        static::addGlobalScope(new NotDeletedScope);
    }
}
