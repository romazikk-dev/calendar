<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerSuspension extends Model
{
    use HasFactory;
    
    protected $table = 'workers_suspensions';
    
    protected $fillable = [
        'worker_id',
        'start',
        'end'
    ];
}
