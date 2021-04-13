<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{
    /**
     * Checks is user .
     *
     * @param  string  $date_string
     * @return string
     */
    protected function parseToCorrectDBDate($date_string){
        if(empty($date_string) || count($date_string_arr = explode('-', $date_string)))
            return $date_string;
        return $date_string_arr[2] . '-' . $date_string_arr[1] . '-' . $date_string_arr[0];
    }
}