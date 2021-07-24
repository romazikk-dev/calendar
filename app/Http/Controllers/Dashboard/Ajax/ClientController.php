<?php

namespace App\Http\Controllers\Dashboard\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\User;
// use App\Models\Hall;
// use App\Models\Worker;
// use App\Models\Template;
// use App\Models\Client;
// use App\Scopes\UserScope;
// use App\Models\TemplateSpecifics;
// use App\Classes\Setting\Enums\Keys as SettingKeys;
// use App\Classes\BookedAndRequested\Retrieval as BookedAndRequestedRetrieval;
// use App\Classes\Range\Range;
use Illuminate\Support\Facades\Validator;
use App\Classes\Getter\Enums\Keys as GetterKeys;
use App\Classes\Getter\Client\Enums\Params as ClientGetterParams;

class ClientController extends Controller
{
    
    public function get(Request $request){
        // var_dump(2222);
        // die();
        $validation_rules = [];
        if($request->has('id')){
            $request_id = $request->get('id');
            if(is_array($request_id)){
                $validation_rules = [
                    'id' => 'nullable|array',
                    'id.*' => 'nullable|integer|exists:clients,id',
                ];
            }else{
                $validation_rules = [
                    'id' => 'nullable|integer|exists:clients,id',
                ];
            }
        }
        
        $validation_rules = array_merge($validation_rules, [
            'with' => 'nullable|array',
            'with.*' => 'nullable|string',
        ]);
        
        $validated = Validator::make($request->all(), $validation_rules)->validate();
        
        $params = [];
        foreach(ClientGetterParams::all(['except' => [ClientGetterParams::OWNER_ID]]) as $getter_param){
            if(!empty($validated[$getter_param]))
                $params[$getter_param] = $validated[$getter_param];
        }
        
        $result = \Getter::of(GetterKeys::CLIENTS)->get($params);
        return response()->json([
            'clients' => $result
        ]);
    }
    
}
