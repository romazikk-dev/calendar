<?php

namespace App\Classes\Holiday;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
use App\Classes\Holiday\Enums\Fields;
use App\Models\Holiday as HolidayModel;
use App\Models\Worker;

class Holiday extends MainHoliday{
    
    public function ofWorker($worker_id, $separate_worker_from_hall = false){
        if(!is_numeric($worker_id))
            return null;
        
        $worker = Worker::find($worker_id);
        if(empty($worker))
            return null;
            
        $worker_holidays = [];
        $hall_holidays = [];
        
        if(!$worker->holidays->isEmpty())
            $worker_holidays = $this->mergeHolidaysAsUnique(
                $worker_holidays,
                $this->parseHolidaysIntoArrayKey($worker->holidays)
            );
        
        if(!$worker->hallsWithoutUserScope->isEmpty())
            foreach($worker->hallsWithoutUserScope as $hall){
                // var_dump($hall->holidays->toArray());
                if(!$hall->holidays->isEmpty()){
                    // var_dump($hall->holidays->toArray());
                    $hall_holidays = $this->mergeHolidaysAsUnique(
                        $hall_holidays,
                        $this->parseHolidaysIntoArrayKey($hall->holidays)
                    );
                }
            }
            
        $null_holidays = $this->getNullHolidaysOfOwner($worker->user_id);
        if(!$null_holidays->isEmpty())
            $hall_holidays = $this->mergeHolidaysAsUnique(
                $hall_holidays,
                $this->parseHolidaysIntoArrayKey($null_holidays)
            );
        
        if($separate_worker_from_hall === false)
            return $this->mergeHolidaysAsUnique(
                $worker_holidays,
                $hall_holidays
            );
            
        return [
            'worker' => $worker_holidays,
            'hall' => $hall_holidays,
        ];
    }
    
    public function getAllAsUniqueDateValue($hall, $worker, $params = []){
        $getKeyFromCarbon = function($carbon_instance){
            return $carbon_instance->format('Y_m_d');
        };
        
        $getHolidaysFromArray = function($holidays_obj, &$arr_to_fill) use ($getKeyFromCarbon) {
            if(!empty($holidays_obj)){
                $holidays_arr = $holidays_obj->toArray();
                if(!empty($holidays_arr)){
                    // var_dump($worker_holidays_arr);
                    foreach($holidays_arr as $holiday){
                        $from_carbon = \Carbon\Carbon::parse($holiday['from']);
                        $to_carbon = \Carbon\Carbon::parse($holiday['to']);
                        // $key = $holiday_to_carbon->format('Y_m_d');
                        $val = $getKeyFromCarbon($from_carbon);
                        if(!in_array($val, $arr_to_fill))
                            $arr_to_fill[] = $val;
                        // $arr_to_fill[$getKeyFromCarbon($from_carbon)] = $holiday;
                        
                        for($i = 0; $i < 1000; $i++){
                            $from_carbon->addDays(1);
                            if($to_carbon->lt($from_carbon))
                                break;
                                
                            $val = $getKeyFromCarbon($from_carbon);
                            if(!in_array($val, $arr_to_fill))
                                $arr_to_fill[] = $val;
                        }
                    }
                }
            }
        };
        
        $worker_holidays = [];
        $getHolidaysFromArray($worker->holidays, $worker_holidays);
        
        $hall_holidays = [];
        $getHolidaysFromArray($hall->holidays, $hall_holidays);
        
        $null_holidays = HolidayModel::where([
            'user_id' => array_key_exists('user_id', $params) && !empty($params['user_id']) ? $params['user_id'] : auth()->user()->id,
            'holidayable_type' => null,
            'holidayable_id' => null,
        ])->get();
        $all_halls_holidays = [];
        $getHolidaysFromArray($null_holidays, $all_halls_holidays);
        
        $hall_holidays = array_unique(array_merge($hall_holidays, $all_halls_holidays));
        
        $total_holidays = array_unique(array_merge($hall_holidays, $worker_holidays));
        
        if(array_key_exists('separate', $params) && $params['separate'] == true){
            return [
                'hall_holidays' => $hall_holidays,
                'worker_holidays' => $worker_holidays,
                'total_holidays' => $total_holidays,
            ];
        }
        
        return $total_holidays;
    }
    
    public function getAllFromRequest($validated = []){
        if(empty($validated)){
            // dd(111);
            $validated[Fields::TITLE] = request()->get(Fields::TITLE);
            $validated[Fields::FROM] = request()->get(Fields::FROM);
            $validated[Fields::TO] = request()->get(Fields::TO);
            $validated[Fields::DESCRIPTION] = request()->get(Fields::DESCRIPTION);
            $validated[Fields::TIMESTAMP] = request()->get(Fields::TIMESTAMP);
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
                    !empty($validated[Fields::TIMESTAMP][$k]) ? $validated[Fields::TIMESTAMP][$k] : time(),
                    'Y-m-d'
                );
                // dd($holiday);
                if(!empty($holiday))
                    $holidays[] = $holiday;
            }
        }
        
        // dd($holidays);
        
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
                    $v->timestamp,
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
                    !empty(${Fields::TIMESTAMP}[$k]) ? ${Fields::TIMESTAMP}[$k] : time(),
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
                        $v->timestamp,
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