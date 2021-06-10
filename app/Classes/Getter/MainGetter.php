<?php

namespace App\Classes\Getter;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;
use App\Classes\Getter\Enums\Keys;
use App\Classes\Getter\Template\Template as TemplateGetter;

class MainGetter{
    
    private $getters = [];
    private $getter = null;
    
    private $aliases = [
        Keys::TEMPLATES => TemplateGetter::class,
    ];
    
    // function __construct() {
    //     // $this->name = $name;
    // }
    
    protected function setGetter(string $getter){
        if(!in_array($getter, Keys::all()) || !array_key_exists($getter, $this->aliases))
            return false;
        
        if(!in_array($getter, $this->getters))
            $this->getters[$getter] = new $this->aliases[$getter]();
            
        return true;
    }
    
    protected function getGetter(string $getter){
        // var_dump($this->getters[$getter]);
        // die();
        
        if(!array_key_exists($getter, $this->getters))
            return null;
            
        return $this->getters[$getter];
    }
    
}