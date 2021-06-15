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
use App\Classes\Range\Range;
// use Illuminate\Support\Facades\Validator;
use App\Classes\Getter\Enums\Keys as GetterKeys;
use App\Classes\Getter\Booking\Enums\GetterTypes;
use App\Classes\Getter\Booking\Enums\Params;

class BookingController extends Controller
{
    
    public function get(Request $request, $start, $end, $type = null){
        $getter = \Getter::of(GetterKeys::BOOKINGS);
        $validation_rules = $getter->getValidationRules(["type" => $type]);
        
        $params = $request->validate($validation_rules);
        // $params["with"] = 'templateWithoutUserScope.specific';
        
        $range = new Range($start, $end, 'month');
        $owner = auth()->user();
        
        if(is_null($type) || $type == GetterTypes::ALL)
            return response()->json([
                'data' => $getter->all($owner, $range, $params),
            ]);
        
        if($type == GetterTypes::FREE)
            return response()->json([
                'data' => $getter->free($owner, $range, (int)$params[Params::HALL], (int)$params[Params::WORKER], $params),
            ]);
    }
    
}
