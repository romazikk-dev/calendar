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
    
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'short_description',
        'notice',
        'country',
        'town',
        'street',
        'business_hours',
    ];
    
    public function workers(){
        return $this->belongsToMany('App\Models\Worker');
        // return $this->belongsToMany('App\Models\Worker');
    }
    
    // public function contacts(){
    //     return $this->hasManyThrough('Contact', 'Account', 'owner_id');
    // }
    
    public function workersWithoutGlobalScope(){
        return $this->belongsToMany('App\Models\Worker')->withoutGlobalScope(UserScope::class);
    }
    
    /**
     * Get the hall's suspension.
     */
    public function suspension()
    {
        return $this->morphOne(Suspension::class, 'suspensionable');
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
     * Get the hall's phones.
     */
    public function phones(){
        return $this->morphMany(Phone::class, 'phoneable');
    }
    
    /**
     * Get the hall's holidays.
     */
    public function holidays(){
        return $this->morphMany(Holiday::class, 'holidayable');
    }
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(){
        static::addGlobalScope(new UserScope);
        static::addGlobalScope(new NotDeletedScope);
    }
    
    /**
     * Scope a query to only include halls of given user.
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
     * Scope a query to only include halls of given id.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeById($query, $id)
    {
        return $query->where('id', $id);
    }
}
