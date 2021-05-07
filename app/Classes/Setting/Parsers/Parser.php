<?php

namespace App\Classes\Setting\Parsers;

use App\Classes\Setting\Enums\Keys;
// use App\Classes\Setting\Default\Default;

class Parser{
    
    /**
     * Parsers
     *
     * @var array
     */
    private $parsers = [];
    
    /**
     * Aliases to parsers clasess per setting
     *
     * @var array
     */
    private $aliases = [
        Keys::DEFAULT_BUSINESS_HOURS => \App\Classes\Setting\Parsers\BussinessHoursParser::class,
        Keys::WORKER_DEFAULT_BUSINESS_HOURS => \App\Classes\Setting\Parsers\BussinessHoursParser::class,
    ];
    
    /**
     * Gets default value per setting.
     *
     * @param  string  $key
     *
     * @return mixed
     */
    public function parse($key, $data){
        // dd(333);
        if(!array_key_exists($key, $this->aliases))
            return null;
        
        // $parser = array_key_exists($key, $this->parsers) ? $this->parsers[$key] : (
        //     array_key_exists($key, $this->aliases) ? 
        // );
        
        if(array_key_exists($key, $this->parsers)){
            $parser = $this->parsers[$key];
        }else{
            if(array_key_exists($key, $this->aliases)){
                $parser_class_name = $this->aliases[$key];
                $parser = new $parser_class_name();
                $this->parsers[$key] = $parser;
            }else{
                return null;
            }
        }
        
        return $parser->parse($data);
        
        
        //     return $this->placeholders[$key];
        // 
        // $placeholder_class_name = $this->aliases[$key];
        // // dd($default_class_name);
        // $placeholder = (new $placeholder_class_name())->get();
        // if(!empty($placeholder))
        //     $this->placeholders[$key] = $placeholder;
        // return $placeholder;
    }
    
}