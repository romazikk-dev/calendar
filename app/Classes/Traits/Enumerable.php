<?php
namespace App\Classes\Traits;

// App\Classes\Traits\Enumerable

trait Enumerable{
    // private static $constCacheArray = NULL;
    
    // static function all($params = []){
    //     if (self::$constCacheArray != NULL) {
    //         return self::$constCacheArray;
    //     }else{
    //         self::$constCacheArray = [];
    //     }
    //     $reflection = new \ReflectionClass(get_class());
    //     $constants = $reflection->getConstants();
    //     foreach($constants as $k => $v){
    //         if(!empty($params['except']) && is_array($params['except'])){
    //             if(!in_array(strtolower($k), $params['except']))
    //                 self::$constCacheArray[strtolower($k)] = $v;
    //         }else{
    //             self::$constCacheArray[strtolower($k)] = $v;
    //         }
    //     }
    //     return self::$constCacheArray;
    // }
    
    static function all($params = []){
        // if (self::$constCacheArray != NULL) {
        //     return self::$constCacheArray;
        // }else{
        //     self::$constCacheArray = [];
        // }
        $all = [];
        $reflection = new \ReflectionClass(get_class());
        $constants = $reflection->getConstants();
        foreach($constants as $k => $v){
            if(!empty($params['except']) && is_array($params['except'])){
                if(!in_array(strtolower($k), $params['except']))
                    $all[strtolower($k)] = $v;
            }else{
                $all[strtolower($k)] = $v;
            }
        }
        return $all;
    }
    
}