<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserScope;

class Template extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function workers(){
        return $this->belongsToMany('App\Models\Worker');
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
    }
}
