<?php
namespace App\Classes\Traits;

// App\Classes\Traits\Enumerable

trait Enumerable{
    private static $constCacheArray = NULL;
    
    static function all(){
        if (self::$constCacheArray != NULL) {
            return self::$constCacheArray;
        }else{
            self::$constCacheArray = [];
        }
        $reflection = new \ReflectionClass(get_class());
        $constants = $reflection->getConstants();
        foreach($constants as $k => $v){
            self::$constCacheArray[strtolower($k)] = $v;
        }
        return self::$constCacheArray;
    }
    
}