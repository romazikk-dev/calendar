<?php

namespace App\Classes\Getter;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;

class Getter extends MainGetter{
    
    public function of(string $getter) {
        if(!$this->setGetter($getter))
            return null;
        
        return $this->getGetter($getter);
    }
    
}