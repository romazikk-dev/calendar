<?php

namespace App\Classes\TemporaryMessages;

use App\Classes\Range\Range;
use App\Classes\TemporaryMessages\Enums\Keys;
use App\Models\TemporaryMessage;

class TemporaryMessages extends MainTemporaryMessages{
    
    public function my(){
        return 'TemporaryMessages';
    }
    
    public function getByKey($key, $as_array = false){
        if(!in_array($key, Keys::all()))
            return null;
            
        $temporary_message = TemporaryMessage::byKey($key)->first();
        
        if(empty($temporary_message))
            return null;
        
        if($as_array === true){
            $temporary_message_arr = $temporary_message->toArray();
            $temporary_message_arr['is_disabled'] = !empty($temporary_message->disabled);
            // dd($temporary_message_arr);
            return $temporary_message_arr;
        }
        
        return $temporary_message;
    }
    
}