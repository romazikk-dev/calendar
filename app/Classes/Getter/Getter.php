<?php

namespace App\Classes\Getter;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;
use App\Classes\Getter\Enums\Keys;

class Getter extends MainGetter{
    
    public function of(string $getter) {
        if(!$this->setGetter($getter))
            return null;
        
        return $this->getGetter($getter);
    }
    
    public function bookings() {
        return $this->of(Keys::BOOKINGS);
    }
    
    public function templates() {
        return $this->of(Keys::TEMPLATES);
    }
    
    public function halls() {
        return $this->of(Keys::HALLS);
    }
    
    public function workers() {
        return $this->of(Keys::WORKERS);
    }
    
}