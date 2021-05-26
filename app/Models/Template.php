<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserScope;
use App\Scopes\NotDeletedScope;

class Template extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    /**
     * Reference to pivot table.
     *
     * @return Model
     */
    public function workers(){
        return $this->belongsToMany('App\Models\Worker');
    }
    
    /**
     * Reference to pivot table.
     *
     * @return Model
     */
    public function specific() {
        return $this->belongsTo('App\Models\TemplateSpecifics', 'specific_id', 'id');
    }
    
    protected $fillable = [
        'user_id',
        'title',
        'duration',
        'price',
        'description',
        'short_description',
        'notice',
        'specific_id',
    ];
    
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
        'duration' => 'Duration',
        'is_deleted' => 'Deleted',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At'
    ];
    
    public function toLabelArray(){
        $arr = [];
        foreach($this->toArray() as $k => $v){
            $arr[$k] = [
                'label' => self::$labels[$k],
                'value' => $k == 'duration' ? $this->duration_parsed : $v,
            ];
        }
        return $arr;
    }
    
    public function getDurationParsedAttribute(){
        return date('H:i', $this->duration);
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
     * Scope a query to only include templates of given user.
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
     * Scope a query to only include templates of given id.
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
