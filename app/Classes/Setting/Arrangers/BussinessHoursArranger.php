<?php
namespace App\Classes\Setting\Arrangers;

class BussinessHoursArranger{
    
    public function arrange($data){
        $count_weekends = 0;
        foreach($data as $k => $v)
            if(!empty($v['is_weekend']) && $v['is_weekend'] === "on")
                $count_weekends++;
        
        return [
            "data" => $data,
            "count_weekends" => $count_weekends,
            "count_workdays" => 7 - $count_weekends
        ];
    }
    
}