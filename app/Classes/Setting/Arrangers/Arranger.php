<?php

namespace App\Classes\Setting\Arrangers;

use App\Classes\Setting\Enums\Keys;
// use App\Classes\Setting\Default\Default;

class Arranger{
    
    /**
     * The arrangers
     *
     * @var array
     */
    private $arrangers = [];
    
    /**
     * Aliases to default clasess with default values per setting
     *
     * @var array
     */
    private $aliases = [
        Keys::DEFAULT_BUSINESS_HOURS => \App\Classes\Setting\Arrangers\BussinessHoursArranger::class,
        Keys::WORKER_DEFAULT_BUSINESS_HOURS => \App\Classes\Setting\Arrangers\BussinessHoursArranger::class,
    ];
    
    /**
     * Gets default value per setting.
     *
     * @param  string  $key
     *
     * @return mixed
     */
     public function arrange($key, $data){
         if(!array_key_exists($key, $this->aliases))
             return null;
         
         // dd($data);
         
         if(array_key_exists($key, $this->arrangers)){
             $arranger = $this->arrangers[$key];
         }else{
             if(array_key_exists($key, $this->aliases)){
                 $arranger_class_name = $this->aliases[$key];
                 $arranger = new $arranger_class_name();
                 $this->arrangers[$key] = $arranger;
             }else{
                 // dd($data);
                 return null;
             }
         }
         
         // dd($data);
         
         return $arranger->arrange($data);
     }
    
}