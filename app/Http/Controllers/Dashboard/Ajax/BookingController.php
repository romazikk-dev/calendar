<?php

namespace App\Http\Controllers\Dashboard\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\User;
use App\Models\Hall;
use App\Models\Worker;
use App\Models\Template;
use App\Models\Client;
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
        
        // var_dump($start, $end);
        // die();
        
        $range = new Range($start, $end, 'month');
        // var_dump($range);
        // die();
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
    
    public function create(Request $request, Client $client, Hall $hall, Template $template, Worker $worker){
        
        // var_dump([
        //     $client->toArray(), $hall->toArray(), $template->toArray(), $worker->toArray()
        // ]);
        // die();
        
        $validated = $request->validate([
            'date' => 'required|string|max:10|regex:/\d{4}-\d{2}-\d{2}/i',
            'time' => 'required|string|max:10|regex:/\d{2}:\d{2}/i',
            'duration' => 'required|string|max:10|regex:/\d{2}:\d{2}/i',
        ]);
        
        $book_on_datetime = $validated['date'] . " " . $validated['time'] . ":00";
        
        // $book_on_carbon = \Carbon\Carbon::parse($book_on_datetime);
        // $current_date_carbon = \Carbon\Carbon::now('Europe/Kiev');
        // 
        // var_dump($book_on_carbon->format('Y-m-d H:i:s'));
        // var_dump($current_date_carbon->format('Y-m-d H:i:s'));
        // die();
        
        // $client = $request->user();
        
        //Check hall
        // if(!($hall = Hall::byId($hall_id)->first()))
        //     return response()->json([
        //         'error' => 'Hall not exist with :id = '.$hall_id
        //     ]);
        
        if(\Suspension::isSuspendedOnDate($hall, $book_on_datetime))
            return response()->json([
                'error' => 'Hall is suspended on ' . $book_on_datetime
            ]);
        
        //Check worker
        // if(!($worker = Worker::byId($worker_id)->first()))
        //     return response()->json([
        //         'error' => 'Worker not exist with :id = ' . $worker_id
        //     ]);
        
        if(\Suspension::isSuspendedOnDate($worker, $book_on_datetime))
            return response()->json([
                'error' => 'Worker is suspended on ' . $book_on_datetime
            ]);
        
        // $all_holidays = \Holiday::getAllAsUniqueDateValue($hall, $worker, [
        //     'user_id' => $user->id
        // ]);
        $all_holidays = \Holiday::getAllAsUniqueDateValue($hall, $worker);
        $for_holiday_check_val = str_replace('-', '_', $validated['date']);
        if(in_array($for_holiday_check_val, $all_holidays))
            return response()->json([
                'error' => 'You trying book on date wich is holiday right now.'
            ]);
            
        // var_dump($validated['book_on_date']);
        // var_dump($for_holiday_check_val);
        // var_dump($all_holidays);
        // var_dump($book_on_datetime);
        // var_dump('success');
        // die();
        
        $template = Template::byId($template->id)
            ->whereHas('workers', function($query) use ($hall, $worker) {
                $query->byId($worker->id)
                    ->whereHas('halls', function($query) use ($hall) {
                        $query->byId($hall->id);
                    });
            })->first();
        
        if(empty($template))
            return response()->json([
                'error' => 'Bad data for :hall, :template, :worker'
            ]);
        
        
        $duration_in_minutes = \Time::parseStringHourMinutesToMinutes($validated['duration']);
        $start = $book_on_datetime;
        $carbon_time = \Carbon\Carbon::parse($book_on_datetime);
        $carbon_time->addMinutes($duration_in_minutes);
        $end = $carbon_time->format('Y-m-d H:i:s');
            
        $bookings = \Getter::of(GetterKeys::BOOKINGS)->all(
            auth()->user(),
            new Range($start, $end, 'month'),
            [
                Params::ONLY_APPROVED => true,
                Params::FIRST_ITEMS => true,
                Params::EXCLUDE_RANGE_START_AND_END_DATES => true,
            ]
        );
        
        if(!empty($bookings))
            return response()->json([
                'error' => 'Wrong time, time is already taken!'
            ]);
        
        // var_dump('success');
        // die();
            
        $booking = Booking::create([
            'user_id' => auth()->user()->id,
            'hall_id' => $hall->id,
            'template_id' => $template->id,
            'worker_id' => $worker->id,
            'client_id' => $client->id,
            'time' => $book_on_datetime,
            'approved' => 1,
            'custom_duration' => \Time::parseStringHourMinutesToMinutes($validated['duration']),
        ]);
        
        return response()->json([
            'status' => 'success'
        ]);
    }
    
    public function edit(Request $request, Booking $booking){
        // return response()->json([
        //     'msg' => 'test'
        // ]);
        
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
