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
use App\Models\Booking;
use App\Classes\Getter\Template\Enums\Params as TemplateParams;

class BookingController extends Controller
{
    
    public function get(Request $request, $start, $end, $type = null){
        // dd(11);
        
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
    
    public function approve(Request $request, Booking $booking){
        if($booking->approved == 1)
            return response()->json([
                'msg' => 'Event is already approved'
            ]);
            
        // var_dump($booking->toArray());
        // die();
        // $duration_in_minutes = \Time::parseStringHourMinutesToMinutes($validated['duration']);
        // $duration = $booking->right_duration;
        $start = $booking->time;
        $carbon_time = \Carbon\Carbon::parse($booking->time);
        $carbon_time->addMinutes($booking->right_duration);
        $end = $carbon_time->format('Y-m-d H:i:s');
        $bookings = \Getter::of(GetterKeys::BOOKINGS)->all(
            auth()->user(),
            new Range($start, $end, 'month'),
            [
                Params::ONLY_APPROVED => true,
                Params::EXCLUDE_IDS => [$booking->id],
                Params::FIRST_ITEMS => true,
                Params::EXCLUDE_RANGE_START_AND_END_DATES => true,
            ]
        );
        
        if(!empty($bookings))
            return response()->json([
                'msg' => 'Event crossing one of other`s events in a day'
            ]);
        
        // var_dump($start, $end, $booking->right_duration);
        // var_dump($bookings);
        // die();
        // return;
        
        $booking->approved = 1;
        $booking->save();
        
        return response()->json([
            'status' => 'success',
            'booking' => $booking,
        ]);
    }
    
    public function delete(Request $request, Booking $booking){
        $booking->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
    
    public function edit(Request $request, Booking $booking){
        $has_item = $request->has('hall') || $request->has('worker') || $request->has('template') ? true : false;
        $item_required = $has_item ? 'required' : 'nullable';
        $validated = $request->validate([
            'hall' => "{$item_required}|integer|exists:halls,id",
            'worker' => "{$item_required}|integer|exists:workers,id",
            'template' => "{$item_required}|integer|exists:templates,id",
            'time' => "{$item_required}|string|date_format:Y-m-d H:i:s",
            'duration' => 'nullable|string|max:10|regex:/\d{2}:\d{2}/i',
        ]);
        
        if($has_item){
            $template = \Getter::of(GetterKeys::TEMPLATES)->get([
                TemplateParams::ID => $validated['template'],
                TemplateParams::WORKER_ID => $validated['worker'],
                TemplateParams::HALL_ID => $validated['hall'],
            ]);
            
            // var_dump($template->toArray());
            // die();
            
            if($template->isEmpty())
                return response()->json([
                    'msg' => 'wrong parameters'
                ]);
        }
        
        // die();
        
        if($has_item){
            $booking->hall_id = $validated['hall'];
            $booking->worker_id = $validated['worker'];
            $booking->template_id = $validated['template'];
        }
        
        if(!empty($validated['time']))
            $booking->time = $validated['time'];
            
        if(!empty($validated['duration'])){
            $duration_in_minutes = \Time::parseStringHourMinutesToMinutes($validated['duration']);
            $start = $booking->time;
            $carbon_time = \Carbon\Carbon::parse($booking->time);
            $carbon_time->addMinutes($duration_in_minutes);
            $end = $carbon_time->format('Y-m-d H:i:s');
            
            $bookings = \Getter::of(GetterKeys::BOOKINGS)->all(
                auth()->user(),
                new Range($start, $end, 'month'),
                [
                    Params::ONLY_APPROVED => true,
                    Params::EXCLUDE_IDS => [$booking->id],
                    Params::FIRST_ITEMS => true,
                    Params::EXCLUDE_RANGE_START_AND_END_DATES => true,
                ]
            );
            
            if(!empty($bookings))
                return response()->json([
                    'msg' => 'wrong parameters'
                ]);
                
            $booking->custom_duration = $duration_in_minutes;
        }
        
        if($booking->approved != 1)
            $booking->approved = 1;
        
        $booking->save();
        
        return response()->json([
            'status' => 'success',
            'booking' => $booking,
        ]);
    }
    
}
