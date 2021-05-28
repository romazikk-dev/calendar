<?php

namespace App\Classes\Specifics;

// use App\Classes\Setting\Enums\Keys;
use App\Models\Setting as Setting;
use App\Models\TemplateSpecifics;

class MainSpecifics{
    
    protected $db_specifics = null;
    protected $db_specifics_as_key_id = null;
    
    protected function setDbSpecifics(){
        if(is_null($this->db_specifics))
            $this->db_specifics = TemplateSpecifics::all();
            
        if(!is_null($this->db_specifics_as_key_id) || empty($this->db_specifics))
            return;
        
        $db_specifics_as_key_id = [];
        foreach($this->db_specifics as $k => $v){
            $db_specifics_as_key_id[$v['id']] = $v;
        }
        
        $this->db_specifics_as_key_id = $db_specifics_as_key_id;
    }
    
}