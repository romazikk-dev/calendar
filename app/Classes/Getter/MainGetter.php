<?php

namespace App\Classes\Getter;

use App\Classes\Getter\Enums\Keys;

class MainGetter{
    
    private $getters = [];
    private $getter = null;
    
    private $aliases = [
        Keys::HALLS => \App\Classes\Getter\Hall\Hall::class,
        Keys::TEMPLATES => \App\Classes\Getter\Template\Template::class,
        Keys::WORKERS => \App\Classes\Getter\Worker\Worker::class,
        Keys::CLIENTS => \App\Classes\Getter\Client\Client::class,
        Keys::BOOKINGS => \App\Classes\Getter\Booking\Booking::class,
    ];
    
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