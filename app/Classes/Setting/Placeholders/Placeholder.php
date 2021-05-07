<?php

namespace App\Classes\Setting\Placeholders;

use App\Classes\Setting\Enums\Keys;
// use App\Classes\Setting\Default\Default;

class Placeholder{
    
    /**
     * The defaults values
     *
     * @var array
     */
    private $placeholders = [];
    
    /**
     * Aliases to default clasess with default values per setting
     *
     * @var array
     */
    private $aliases = [
        Keys::DEFAULT_BUSINESS_HOURS => \App\Classes\Setting\Placeholders\BussinessHoursPlaceholder::class,
        Keys::WORKER_DEFAULT_BUSINESS_HOURS => \App\Classes\Setting\Placeholders\BussinessHoursPlaceholder::class,
    ];
    
    /**
     * Gets default value per setting.
     *
     * @param  string  $key
     *
     * @return mixed
     */
    public function get($key){
        // dd(333);
        if(!array_key_exists($key, $this->aliases))
            return null;
            
        if(array_key_exists($key, $this->placeholders))
            return $this->placeholders[$key];
            
        $placeholder_class_name = $this->aliases[$key];
        // dd($default_class_name);
        $placeholder = (new $placeholder_class_name())->get();
        if(!empty($placeholder))
            $this->placeholders[$key] = $placeholder;
        return $placeholder;
    }
    
}