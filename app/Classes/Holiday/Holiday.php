<?php

namespace App\Classes\Holiday;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
use App\Classes\Holiday\Enums\Fields;
use App\Models\Holiday as HolidayModel;

class Holiday extends MainHoliday{
    
    public function getAllFromRequest($validated = []){
        if(empty($validated)){
            // dd(111);
            $validated[Fields::TITLE] = request()->get(Fields::TITLE);
            $validated[Fields::FROM] = request()->get(Fields::FROM);
            $validated[Fields::TO] = request()->get(Fields::TO);
            $validated[Fields::DESCRIPTION] = request()->get(Fields::DESCRIPTION);
            // dd(Fields::TITLE);
        }
        $holidays = [];
        if(!empty($validated[Fields::TITLE])){
            // $holidays = [];
            // dd(11);
            foreach($validated[Fields::TITLE] as $k => $v){
                $holiday = $this->getHoliday(
                    !empty($validated[Fields::TITLE][$k]) ? $validated[Fields::TITLE][$k] : null,
                    !empty($validated[Fields::FROM][$k]) ? $validated[Fields::FROM][$k] : null,
                    !empty($validated[Fields::TO][$k]) ? $validated[Fields::TO][$k] : null,
                    !empty($validated[Fields::DESCRIPTION][$k]) ? $validated[Fields::DESCRIPTION][$k] : null,
                    'Y-m-d'
                );
                // dd($holiday);
                if(!empty($holiday))
                    $holidays[] = $holiday;
            }
        }
        
        return $holidays;
    }
    
    public function getAllNullsForVue(){
        if($this->hasOld())
            return $this->getOld();
            
        // if(old(Fields::TITLE)){
        //     ${Fields::TITLE} = old(Fields::TITLE);
        //     ${Fields::FROM} = old(Fields::FROM);
        //     ${Fields::TO} = old(Fields::TO);
        //     ${Fields::DESCRIPTION} = old(Fields::DESCRIPTION);
        // 
        //     $holidays = [];
        //     foreach(${Fields::TITLE} as $k => $v){
        //         $holiday = $this->getHoliday(
        //             !empty(${Fields::TITLE}[$k]) ? ${Fields::TITLE}[$k] : null,
        //             !empty(${Fields::FROM}[$k]) ? ${Fields::FROM}[$k] : null,
        //             !empty(${Fields::TO}[$k]) ? ${Fields::TO}[$k] : null,
        //             !empty(${Fields::DESCRIPTION}[$k]) ? ${Fields::DESCRIPTION}[$k] : null,
        //         );
        //         if(!empty($holiday))
        //             $holidays[] = $holiday;
        //     }
        //     return $holidays;
        // }
        
        // dd(111);
        
        $holidays_nulled_collection = $this->getAllNulls();
        if (!$holidays_nulled_collection->isEmpty()){
            // dd($holidays_nulled_collection);
            $holidays = [];
            foreach($holidays_nulled_collection as $v){
                // dd($v->title);
                $holiday = $this->getHoliday(
                    $v->title,
                    $v->from,
                    $v->to,
                    $v->description,
                    true
                );
                // dd($holiday);
                if(!empty($holiday))
                    $holidays[] = $holiday;
            }
            return $holidays;
        }
        
        return [];
    }
    
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
    
    public function getValidationRules(){
        return $this->validation_rules;
    }
    
}