<?php

namespace App\Classes\Suspension;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Booking;
// use App\Models\User;
// use App\Models\Hall;
// use App\Models\Template;
// use App\Models\Suspension;
// use App\Exceptions\Api\Calendar\BadRangeException;
use App\Classes\Range\Range;
use App\Classes\Suspension\Enums\Types;

class Suspension extends MainSuspension{
    
    public function getOldForVue(){
        if(old('status')){
            $old_suspension = [
                'status' => old('status'),
                'suspend_from' => old('suspend_from'),
                'suspend_to' => old('suspend_to'),
                'count_status_error' => null,
            ];
            if($old_suspension['status'] == 'period'){
                if(
                    (empty($old_suspension['suspend_from']) && !empty($old_suspension['suspend_to'])) ||
                    (!empty($old_suspension['suspend_from']) && empty($old_suspension['suspend_to']))
                ){
                    $old_suspension['count_status_error'] = 1;
                }else if(empty($old_suspension['suspend_from']) && empty($old_suspension['suspend_to'])){
                    $old_suspension['count_status_error'] = 2;
                }
            }
            return $old_suspension;
        }else{
            return null;
        }
    }
    
    // public function getNotice($suspension_model){
    //     if(is_null($suspension_model))
    //         return Types::DISABLE;
    //     if(empty($suspension_model->from) && empty($suspension_model->to))
    //         return Types::COMPLETE;
    //     if(!empty($suspension_model->from) && !empty($suspension_model->to))
    //         return Types::PERIOD;
    // }
    
    public function toogle(
        string $type,
        $model,
        $start_date = null,
        $end_date = null
    ){
        
        if($this->type == Types::COMPLETE)
            $this->completelySuspend($model);
        if($this->type == Types::PERIOD)
            $this->periodSuspend($model, $start_date, $end_date);
        if($this->type == Types::DISABLE)
            $this->disableSuspend($model);
        
    }
    
}