<?php

namespace App\Http\Controllers\Dashboard\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Classes\Getter\Enums\Keys as GetterKeys;
use App\Classes\Getter\Template\Enums\Params as TemplateGetterParams;

class TemplateController extends Controller
{
    
    public function get(Request $request){
        $validation_rules = [];
        foreach([
            ['key' => 'id', 'table' => 'templates'],
            ['key' => 'hall_id', 'table' => 'halls'],
            ['key' => 'worker_id', 'table' => 'workers'],
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
        
        $validator = Validator::make($request->all(), $validation_rules);
        
        if($validator->fails())
            return response()->json(false);
        
        $validated = $validator->valid();
        
        $params = [];
        foreach(TemplateGetterParams::all(['except' => [TemplateGetterParams::OWNER_ID]]) as $getter_param){
            if(!empty($validated[$getter_param]))
                $params[$getter_param] = $validated[$getter_param];
        }
        
        if(empty($params[TemplateGetterParams::WITH])){
            $params[TemplateGetterParams::WITH] = ['specific'];
        }
        
        $result = \Getter::of(GetterKeys::TEMPLATES)->get($params);
        return response()->json(['templates' => $result]);
    }
    
}
