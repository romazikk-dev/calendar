<?php

namespace App\Classes\PhonePicker;

use App\Models\Worker;
use App\Classes\Enums\PhoneTypes;
use App\Classes\PhonePicker\Enums\IndexPrefixes;

class MainPhone{
    
    // protected $index_prefixes_aliases = [
    //     "value" => IndexPrefixes::VALUE,
    //     "id" => IndexPrefixes::ID,
    //     "type" => IndexPrefixes::TYPE,
    //     "custom_type" => IndexPrefixes::CUSTOM_TYPE,
    // ];
    
    // function __construct($name){
    //     This->setIndex_prefixes_aliases
    // }
    
    protected function getFullIndex($type, $numeric_index){
        if(array_key_exists($type, IndexPrefixes::all()))
            return $this->index_prefixes_aliases[$type] . $numeric_index;
        return null;
    }
    
}