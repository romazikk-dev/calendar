<?php

// $controller_name = request()->route()->getActionName();
// dd($controller_name);

if (! function_exists('currentController')) {
    function controllerFromFullClassName($str) {
        $arr = explode('@', $str);
        $arr2 = explode('\\', $arr[0]);
        $full_controller_name = end($arr2);
        $only_controller_name = str_replace("controller", "", strtolower($full_controller_name));
        return $only_controller_name;
    }
}