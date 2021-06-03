<?php

namespace App\Classes\Suspension;

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
    
    public function isSuspendedOnDate($model, $date){
        if(empty($model->suspension))
            return false;
        
        $suspension = $model->suspension->toArray();
        if(empty($suspension))
            return false;
            
        // var_dump($suspension);
        // die();
            
        if(is_null($suspension['from']) && is_null($suspension['to']))
            return true;
            
        if(!is_null($suspension['from']) && !is_null($suspension['to'])){
            $carbon_date = \Carbon\Carbon::parse($date);
            $carbon_from = \Carbon\Carbon::parse($suspension['from']);
            $carbon_to = \Carbon\Carbon::parse($suspension['to']);
            if($carbon_date->lte($carbon_to) && $carbon_from->lte($carbon_date)){
                return true;
            }
        }
        
        return false;
        // var_dump($this->worker->suspension->toArray());
        // die();
        // return false;
    }
    
    public function toogle(
        string $type,
        $model,
        $start_date = null,
        $end_date = null
    ){
        
        if($type == Types::COMPLETE)
            $this->completelySuspend($model);
        if($type == Types::PERIOD)
            $this->periodSuspend($model, $start_date, $end_date);
        if($type == Types::DISABLE)
            $this->disableSuspend($model);
        
    }
    
}