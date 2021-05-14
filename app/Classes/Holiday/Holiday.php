<?php

namespace App\Classes\Holiday;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
use App\Classes\Holiday\Enums\Fields;

class Holiday extends MainHoliday{
    
    public function getAllForVue($model = null){
        if(old(Fields::TITLE)){
            ${Fields::TITLE} = old(Fields::TITLE);
            ${Fields::FROM} = old(Fields::FROM);
            ${Fields::TO} = old(Fields::TO);
            ${Fields::DESCRIPTION} = old(Fields::DESCRIPTION);
            
            $holidays = [];
            foreach(${Fields::TITLE} as $k => $v){
                $holiday = $this->getHoliday(
                    !empty(${Fields::TITLE}[$k]) ? ${Fields::TITLE}[$k] : null,
                    !empty(${Fields::FROM}[$k]) ? ${Fields::FROM}[$k] : null,
                    !empty(${Fields::TO}[$k]) ? ${Fields::TO}[$k] : null,
                    !empty(${Fields::DESCRIPTION}[$k]) ? ${Fields::DESCRIPTION}[$k] : null,
                );
                if(!empty($holiday))
                    $holidays[] = $holiday;
            }
            return $holidays;
        }
        
        if(!is_null($model)){
            // dd(888);
            $model_holidays = $model->holidays;
            // dd($model_holidays);
            $holidays = [];
            if(!empty($model_holidays)){
                foreach($model_holidays as $k => $v){
                    $holiday = $this->getHoliday(
                        $v->title,
                        $v->from,
                        $v->to,
                        $v->description,
                        true
                    );
                    if(!empty($holiday))
                        $holidays[] = $holiday;
                }
            }
            return $holidays;
        }
        
        return [];
    }
    
    public function my(){
        dd(8888888888);
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