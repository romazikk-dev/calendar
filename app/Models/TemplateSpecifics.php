<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsToMany('App\Models\Template', 'specific_template', 'specific_id', 'template_id');
    }
}
