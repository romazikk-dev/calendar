<?php

namespace App\Http\Controllers\Api\Calendar\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Client;
// use DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller{
    
    public function register(Request $request){
        $validated = $request->validate([
            'user_id' => 'exists:users,id',
            'first_name' => 'required|max:255',
            'last_name' => 'max:255',
            'phone' => 'nullable|regex:/^\+[0-9]{3,20}$/i',
            'email' => [
                'required', 'email', ['max', 255], Rule::unique('clients')->where(function($query) {
                    // $query->where('is_deleted', '=', '0')->where('id', '!=', $id);
                    $query->where('is_deleted', '=', '0');
                })
            ],
            'password' => 'required|max:255',
            'password_confirm' => 'required|same:password',
        ]);
        
        // $validated['user_id'] = auth()->user()->id;
        $validated['password'] = Hash::make($validated['password']);
        unset($validated['password_confirm']);
        
        // 
        $client = Client::create($validated);
        
        $token = $client->createToken('my');
        // echo  $token->plainTextToken;
        // 
        // return redirect()->route('dashboard.worker.index');
        // var_dump($request->post());
        return response()->json([
            'msg' => 'Client successfuly created',
            'status' => 'success',
            'token' =>  $token->plainTextToken,
        ]);
    }
    
    public function login(Request $request){
        $validated = $request->validate([
            'user_id' => 'exists:users,id',
            'email' => 'required|email|max:255',
            'password' => 'required|max:255'
        ]);
        
        $client = Client::where('email', $validated['email'])
                    ->where('is_deleted', '0')
                    ->first();
                    
        if (!$client || !Hash::check($validated['password'], $client->password))
            return response('Login invalid', 503);
        
        $token = $client->createToken('my');
        
        return response()->json([
            'msg' => 'Client successfuly authorized',
            'status' => 'success',
            'token' =>  $token->plainTextToken,
        ]);
    }
    
}
