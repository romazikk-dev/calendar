<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Scopes\UserScope;
use App\Scopes\NotDeletedScope;

class Worker extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $guard = 'worker';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'phone',
        'birthdate',
        'country',
        'town',
        'street',
        'email',
        'password',
        'business_hours',
    ];
    
    /**
     * The labels for attributes.
     *
     * @var array
     */
    public static $labels = [
        'id' => 'ID',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'gender' => 'Gender',
        'phone' => 'Phone',
        'birthdate' => 'Birthdate',
        'country' => 'Country',
        'town' => 'Town',
        'street' => 'Street',
        'email' => 'E-mail',
        'password' => 'Password',
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
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute() {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'is_deleted',
        'user_id'
    ];
    
    /**
     * Reference to pivot table.
     *
     * @return Model
     */
    public function halls() {
        return $this->belongsToMany('App\Models\Hall');
    }
    
    /**
     * Reference to pivot table.
     *
     * @return Model
     */
    public function hallsWithoutUserGlobalScope() {
        return $this->belongsToMany('App\Models\Hall')->withoutGlobalScope(UserScope::class);
    }
    
    /**
     * Reference to pivot table.
     *
     * @return Model
     */
    public function hallsWithoutUserScope() {
        return $this->belongsToMany('App\Models\Hall')->withoutGlobalScope(UserScope::class);
    }
    
    /**
     * Get the worker's suspension.
     */
    public function suspension(){
        return $this->morphOne(Suspension::class, 'suspensionable');
    }
    
    /**
     * Reference to pivot table.
     *
     * @return Model
     */
    public function templates() {
        return $this->belongsToMany('App\Models\Template');
    }
    
    /**
     * Reference to pivot table.
     *
     * @return Model
     */
    public function templatesWithSpecific() {
        return $this->belongsToMany('App\Models\Template')->with('specific');
    }
    
    /**
     * Get the post's image.
     */
    public function phones(){
        return $this->morphMany(Phone::class, 'phoneable');
    }
    
    /**
     * Get the worker's holidays.
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
     * Scope a query to only include workers of given user.
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
     * Scope a query to only include workers of given users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUsers($query, $user_ids)
    {
        return $query->whereIn('user_id', $user_ids);
    }
    
    /**
     * Scope a query to only include workers of given id.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeById($query, $id)
    {
        return $query->where('id', $id);
    }
    
    /**
     * Scope a query to only include workers of given ids.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByIds($query, $ids)
    {
        return $query->whereIn('id', $ids);
    }
        
}
