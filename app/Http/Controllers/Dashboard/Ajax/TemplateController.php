<?php

namespace App\Http\Controllers\Dashboard\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hall;
use App\Models\Worker;
use App\Models\Template;
use App\Models\Client;
use App\Scopes\UserScope;
use App\Models\TemplateSpecifics;
use App\Classes\Setting\Enums\Keys as SettingKeys;
use App\Classes\BookedAndRequested\Retrieval as BookedAndRequestedRetrieval;
use App\Classes\Range\Range;
use Illuminate\Support\Facades\Validator;
use App\Classes\Getter\Enums\Keys as GetterKeys;

class TemplateController extends Controller
{
    
    public function get(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|numeric|exists:templates,id',
            'hall_id' => 'nullable|numeric|exists:halls,id',
            'worker_id' => 'nullable|numeric|exists:workers,id',
        ]);
        
        if($validator->fails())
            return response()->json(false);
        
        $params = $validator->valid();
        $params['with'] = 'specific';
        $templates = \Getter::of(GetterKeys::TEMPLATES)->get($params);
        
        return response()->json(['templates' => $templates]);
    }
    
}
