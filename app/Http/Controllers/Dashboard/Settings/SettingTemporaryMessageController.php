<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\TemporaryMessages\Enums\Keys as TemporaryMessagesKeys;
use App\Models\Disabled;
use Illuminate\Support\Facades\Validator;

class SettingTemporaryMessageController extends Controller
{
    
    function disable(Request $request){
        $keys_arr = TemporaryMessagesKeys::all();
        $keys_str = implode(',', $keys_arr);
        
        $validator = Validator::make($request->all(), [
            'key' => ['required', 'in:' . $keys_str, 'exists:temporary_messages,key'],
        ]);
        
        if($validator->fails())
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        
        $validated = $validator->valid();
        
        // $validated = $request->validate([
        //     'key' => ['required', 'in:' . $keys_str, 'exists:temporary_messages,key'],
        // ]);
        
        // var_dump($validated);
        // die();

        $temp_msg = \TempMsgs::getByKey($validated['key']);
        if(empty($temp_msg->disabled))
            $temp_msg->disabled()->create();
        
        return response()->json([
            'status' => 'success'
        ]);
        // var_dump($temp_msg);
        // die();
    }
    
}