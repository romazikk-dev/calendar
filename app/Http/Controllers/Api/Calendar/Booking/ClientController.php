<?php

namespace App\Http\Controllers\Api\Calendar\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller{
    
    public function info(Request $request){
        return response()->json($request->user()->toArray());
    }
    
}
