<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserScope;

// use App\Models\TemplateSpecifics;

class TemplateSpecifics extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'parent_id',
        'ids_trace',
        'title',
        'description',
    ];
    
    /**
     * Reference to pivot table.
     *
     * @return Model
     */
    public function templates(){
        // return $this->belongsToMany('App\Models\Template', 'templates', 'id', 'specific_id');
        // return $this->belongsToMany('App\Models\Template');
        return $this->hasMany('App\Models\Template', 'specific_id');
    }
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(){
        static::addGlobalScope(new UserScope);
        
        static::creating(function ($specific) {
            $specific->user_id = auth()->user()->id;
        });
    }
}
