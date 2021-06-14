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

class ClientController extends Controller
{
    
    public function get(Request $request){
        
        // var_dump(2222);
        // die();
        
        $validation_rules = [
            'id' => 'nullable|numeric|exists:clients,id',
            'with' => 'nullable|array',
        ];
        
        $params = Validator::make($request->only(array_keys($validation_rules)), $validation_rules)
            ->validate();
        
        $clients = \Getter::of(GetterKeys::CLIENTS)->get($params);
        
        return response()->json([
            'clients' => $clients
        ]);
    }
    
}
