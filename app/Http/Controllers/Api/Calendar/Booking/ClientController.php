<?php

namespace App\Http\Controllers\Api\Calendar\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;

class ClientController extends Controller{
    
    public function info(Request $request, User $user){
    // public function info(Request $request){
        // $client_data = $request->user()->toArray();
        // Booking::where
        
        // echo 111;
        // die();
        
        return response()->json($request->user()->toArray());
    }
    
}
