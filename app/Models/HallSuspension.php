<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallSuspension extends Model
{
    use HasFactory;
    
    protected $table = 'hall_suspension';
    
    protected $fillable = [
        'hall_id',
        'from',
        'to'
    ];
}
