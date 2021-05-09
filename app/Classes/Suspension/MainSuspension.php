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
use App\Classes\Suspension\Enums\Types;

class MainSuspension{
    
    protected function periodSuspend($model, $start_date, $end_date){
        if(!empty($model->suspension)){
            $model->suspension->update([
                'from' => \Carbon\Carbon::parse($start_date)->format('Y-m-d 00:00:00'),
                'to' => \Carbon\Carbon::parse($end_date)->format('Y-m-d 23:59:59'),
            ]);
        }else{
            $model->suspension()->create([
                'from' => \Carbon\Carbon::parse($start_date)->format('Y-m-d 00:00:00'),
                'to' => \Carbon\Carbon::parse($end_date)->format('Y-m-d 23:59:59'),
            ]);
        }
    }
    
    protected function completelySuspend($model){
        if(!empty($model->suspension)){
            $model->suspension()->update([
                'from' => null,
                'to' => null,
            ]);
        }else{
            $model->suspension()->create([
                'from' => null,
                'to' => null,
            ]);
        }
    }
    
    protected function disableSuspend($model){
        if(!empty($model->suspension))
            $model->suspension->forceDelete();
    }
    
}