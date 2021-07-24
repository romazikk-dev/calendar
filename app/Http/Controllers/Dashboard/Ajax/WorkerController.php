<?php

namespace App\Http\Controllers\Dashboard\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Classes\Getter\Enums\Keys as GetterKeys;
use App\Classes\Getter\Worker\Enums\Params as WorkerGetterParams;

class WorkerController extends Controller
{
    
    public function get(Request $request){
        $validation_rules = [];
        foreach([
            ['key' => 'id', 'table' => 'workers'],
            ['key' => 'template_id', 'table' => 'templates'],
            ['key' => 'hall_id', 'table' => 'halls'],
        ] as $p){
            if($request->has($p['key'])){
                $request_item = $request->get($p['key']);
                if(is_array($request_item)){
                    $validation_rules[$p['key']] = 'nullable|array';
                    $validation_rules[$p['key'] . '.*'] = 'nullable|integer|exists:' . $p['table'] . ',id';
                }else{
                    $validation_rules[$p['key']] = 'nullable|numeric|exists:' . $p['table'] . ',id';
                }
            }
        }
        
        $validation_rules = array_merge($validation_rules, [
            'with' => 'nullable|array',
            'with.*' => 'nullable|string',
        ]);
        
        $validated = Validator::make($request->all(), $validation_rules)->validate();
            
        $params = [];
        foreach(WorkerGetterParams::all(['except' => [WorkerGetterParams::OWNER_ID]]) as $getter_param){
            if(!empty($validated[$getter_param]))
                $params[$getter_param] = $validated[$getter_param];
        }
        
        $result = \Getter::of(GetterKeys::WORKERS)->get($params);
        return response()->json([
            'workers' => $result
        ]);
    }
    
}
