<?php

namespace App\Exceptions\Api\Calendar;

use Exception;

// use App\Exceptions\Api\Calendar\BadRangeException;

class BadRangeException extends Exception
{
    protected $message = 'wrong range';
    
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        // return 111;
        // $request->setJson(['error' => '111']);
        $msg = $this->getClassName() . ': ' . $this->getMessage();
        if ($request->wantsJson()) {
            return response()->json([$msg]);
        } else {
            return $msg;
        }
    }
    
    protected function getClassName(){
        $class_name = self::class;
        $class_name_arr = explode('\\', self::class);
        return end($class_name_arr);
    }
}
