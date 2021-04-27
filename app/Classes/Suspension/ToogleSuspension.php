<?php

namespace App\Classes\Suspension;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Booking;
// use App\Models\User;
// use App\Models\Hall;
// use App\Models\Template;
use App\Models\Suspension;
// use App\Exceptions\Api\Calendar\BadRangeException;
use App\Classes\Range\Range;

class ToogleSuspension{
    
    protected $type = null;
    protected $model = null;
    protected $start_date = null;
    protected $end_date = null;
    
    const COMPLETE_SUSPENSION = "complete";
    const PERIOD_SUSPENSION = "period";
    const DISABLE_SUSPENSION = "disable";
    
    function __construct(
        string $type,
        $model,
        $start_date,
        $end_date
    ) {
        
        $this->type = $type;
        $this->model = $model;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        
    }
    
    protected function periodSuspend(){
        if(!empty($this->model->suspension)){
            $this->model->suspension->update([
                'from' => \Carbon\Carbon::parse($this->start_date)->format('Y-m-d 00:00:00'),
                'to' => \Carbon\Carbon::parse($this->end_date)->format('Y-m-d 23:59:59'),
            ]);
        }else{
            // $this->model->suspension->update([
            //     'from' => \Carbon\Carbon::parse($this->start_date)->format('Y-m-d 00:00:00'),
            //     'to' => \Carbon\Carbon::parse($this->start_date)->format('Y-m-d 23:59:59'),
            // ])
            $this->model->suspension()->create([
                'from' => \Carbon\Carbon::parse($this->start_date)->format('Y-m-d 00:00:00'),
                'to' => \Carbon\Carbon::parse($this->end_date)->format('Y-m-d 23:59:59'),
            ]);
        }
    }
    
    protected function completelySuspend(){
        if(!empty($this->model->suspension)){
            $this->model->suspension()->update([
                'from' => null,
                'to' => null,
            ]);
        }else{
            $this->model->suspension()->create([
                'from' => null,
                'to' => null,
            ]);
        }
    }
    
    protected function disableSuspend(){
        if(!empty($this->model->suspension))
            $this->model->suspension->forceDelete();
    }
    
    // public static function isSuspended(Suspension $suspension){
    //     if($suspension->from == null && $suspension->to == null)
    //     dd($suspension);
    // }
    
    public function toogle(){
        
        if($this->type == self::COMPLETE_SUSPENSION)
            $this->completelySuspend();
        if($this->type == self::PERIOD_SUSPENSION)
            $this->periodSuspend();
        if($this->type == self::DISABLE_SUSPENSION)
            $this->disableSuspend();
        
    }
    
    public function getFromDate(){
        
    }
    
    public function getToDate(){
        
    }
    
}