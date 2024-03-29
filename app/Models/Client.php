<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Scopes\UserScope;
use App\Scopes\NotDeletedScope;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    
    protected $guard = 'client';
    
    protected $guarded = [];
    
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'birthdate',
        'country',
        'town',
        'street',
        'email',
        'password',
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
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'is_deleted',
        'user_id'
    ];
    
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
     * Get the client's phones.
     */
    public function phones(){
        return $this->morphMany(Phone::class, 'phoneable');
    }
    
    /**
     * Get the client's suspension.
     */
    public function suspension(){
        return $this->morphOne(Suspension::class, 'suspensionable');
    }
    
    /**
     * Scope a query to only include clients of given id.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeById($query, $id)
    {
        return $query->where('id', $id);
    }
    
    /**
     * Scope a query to only include clients of given ids.
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
