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
use App\Classes\Setting\Enums\Keys as SettingsKeys;
use App\Classes\Setting\Settings\AdminsBookingCalendar\Enums\MainKeys as AdminsBookingCalendarMainSettingKeys;
// use App\Classes\BookedAndRequested\Retrieval as BookedAndRequestedRetrieval;
use App\Classes\Range\Range;
// use Illuminate\Support\Facades\Validator;
use App\Classes\Getter\Enums\Keys as GetterKeys;
use App\Classes\Getter\Booking\Enums\GetterTypes;
use App\Classes\Getter\Booking\Enums\Params;
use App\Classes\Getter\Booking\Enums\FreeWithEventsParams;
use App\Models\Booking;
use App\Classes\Getter\Template\Enums\Params as TemplateParams;
use App\Rules\DurationRange;

class BookingController extends Controller
{
    public function free(Request $request, $start, $end){
        $validated = $request->validate([
            'worker' => 'required|integer|exists:workers,id',
            'client' => 'required|integer|exists:clients,id',
            'exclude_ids' => 'nullable|array',
            'exclude_ids.*' => 'nullable|integer|exists:bookings,id',
            'with' => 'nullable|array',
            'with.*' => 'nullable|string',
            'with_events' => 'nullable|string|in:per_client,all',
            'booking_any_time' => 'nullable|in:true',
        ]);
        
        // $getter = \Getter::of(GetterKeys::BOOKINGS);
        // $range = new Range($start, $end, 'month');
        
        // $worker_getter = \Getter::of(GetterKeys::WORKERS)->get([
        //     'id' => $params['worker'][0],
        //     'with' => ['hallsWithoutUserGlobalScope'],
        // ])->toArray();
        // var_dump($worker_getter);
        // var_dump($params);
        // die();
        
        // var_dump($validated);
        // die();
        $calendar_main_setting = \Setting::of(SettingsKeys::ADMINS_BOOKING_CALENDAR_MAIN)->getOrPlaceholder();
        
        // var_dump($calendar_main_setting[AdminsBookingCalendarMainSettingKeys::ENABLE_BOOKING_ON_ANY_TIME]);
        // die();
        
        $params = [];
        $params[Params::WITH] = [
            'templateWithoutUserScope.specific', 'workerWithoutUserScope',
            'hallWithoutUserScope', 'clientWithoutUserScope'
        ];
        foreach([
            Params::WORKER, Params::EXCLUDE_IDS, Params::WITH
        ] as $getter_param){
            if(!empty($validated[$getter_param]))
                $params[$getter_param] = $validated[$getter_param];
        }
        
        if(!empty($validated['with_events'])){
            $params[Params::FREE_WITH_EVENTS] = true;
            if($validated['with_events'] == 'per_client')
                $params[Params::FREE_WITH_EVENTS_PER_CLIENT] = $validated['client'];
        }
        
        if(!empty($calendar_main_setting[AdminsBookingCalendarMainSettingKeys::ENABLE_BOOKING_ON_ANY_TIME]) &&
        !empty($validated['booking_any_time']) && $validated['booking_any_time'] == "true"){
            $params[Params::FREE_SHOW_ALL_TIMES] = true;
        }
        
        // $params[Params::FREE_SHOW_ALL_TIMES] = true;
        
        // var_dump($params);
        // die();
        
        // $params[Params::WITH_EVENTS_PER_CLIENT] = $validated['client'];
        // $params[Params::CLIENT] = $validated['client'];
        
        // var_dump($params);
        // die();
        
        // $range = new Range($start, $end, 'month');
        
        // var_dump($_GET);
        // die();
        
        // var_dump(\Getter::bookings()->free(
        //     new Range($start, $end, 'month'),
        //     $params
        // ));
        // die();
        
        // var_dump($params);
        // die();
        
        return response()->json([
            'data' => \Getter::bookings()->free(
                new Range($start, $end, 'month'),
                $params
            ),
        ]);
    }
    
    public function get(Request $request, $start, $end, $type = null){
        $validation_rules = [];
        foreach([
            ['key' => 'hall', 'table' => 'halls'],
            ['key' => 'worker', 'table' => 'workers'],
            ['key' => 'template', 'table' => 'templates'],
            ['key' => 'client', 'table' => 'clients'],
        ] as $r){
            if($request->has($r['key'])){
                $request_item = $request->get($r['key']);
                if(is_array($request_item)){
                    $validation_rules = [
                        $r['key'] => 'nullable|array',
                        $r['key'] . '.*' => 'nullable|integer|exists:' . $r['table'] . ',id',
                    ];
                }elseif(is_numeric($request_item)){
                    $validation_rules = [
                        $r['key'] => 'nullable|integer|exists:' . $r['table'] . ',id',
                    ];
                }
            }
        }
        
        $validation_rules = array_merge($validation_rules, [
            'status' => 'nullable|array',
            'status.*' => 'nullable|string',
            'with' => 'nullable|array',
            'with.*' => 'nullable|string',
            'exclude_ids' => 'nullable|array',
            'exclude_ids.*' => 'nullable|integer|exists:bookings,id',
        ]);
        
        // var_dump($validation_rules);
        // die();
        
        $duration_range = \DurationRange::get();
        if($request->has('duration_start')){
            $duration_start = (int)$request->get('duration_start');
            if($request->has('duration_end') &&
            $duration_start <= (int)$request->get('duration_end')){
                $duration_start_maximum = (int)$request->get('duration_end');
            }
            $validation_rules['duration_start'] =
                'nullable|integer|min:' . $duration_range['start'] .
                (!empty($duration_start_maximum) ? '|max:' . $duration_start_maximum : '');
        }
        
        if($request->has('duration_end')){
            $duration_end = (int)$request->get('duration_end');
            if($request->has('duration_start') &&
            $duration_end >= (int)$request->get('duration_start')){
                $duration_start_minimum = (int)$request->get('duration_start');
                $validation_rules['duration_end'] =
                    'nullable|integer|min:' . $duration_range['start'] .
                    (!empty($duration_start_maximum) ? '|max:' . $duration_start_maximum : '');
            }
            if(!$request->has('duration_start')){
                $validation_rules['duration_end'] =
                    'nullable|integer|min:0|max:' . $duration_end . '';
            }
        }
        
        $params = $request->validate($validation_rules);
        // $getter = \Getter::of(GetterKeys::BOOKINGS);
        $getter = \Getter::bookings();
        
        $range = new Range($start, $end, 'month');
        // $owner = auth()->user();
        
        // var_dump($params);
        // die();
        
        if(is_null($type) || $type == GetterTypes::ALL)
            return response()->json([
                'data' => $getter->all($range, $params),
            ]);
        
        if($type == GetterTypes::FREE){
            // var_dump($params[Params::HALL]);
            var_dump($params);
            die();
            $worker_getter = \Getter::of(GetterKeys::WORKERS)->get([
                'id' => $params['worker'][0],
                'with' => ['hallsWithoutUserGlobalScope'],
            ])->toArray();
            var_dump($worker_getter);
            var_dump($params);
            die();
            unset($params['template'], $params['hall']);
            return response()->json([
                'data' => $getter->free($owner, $range, (int)$params[Params::HALL][0], (int)$params[Params::WORKER][0], $params),
            ]);
        }
    }
    
    public function approve(Request $request, Booking $booking){
        if($booking->approved == 1)
            return response()->json([
                'msg' => 'Event is already approved'
            ]);
            
        if(!$booking->isFitsInTime())
            return response()->json([
                'error' => $booking->getNotFitsInTimeReason()
            ]);
        
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
        $validated = $request->validate([
            'date' => 'required|string|max:10|regex:/\d{4}-\d{2}-\d{2}/i',
            'time' => 'required|string|max:10|regex:/\d{2}:\d{2}/i',
            // 'duration' => 'required|string|max:10|regex:/\d{2}:\d{2}/i',
            'duration' => [
                'required', 'string', 'max:10', 'regex:/\d{2}:\d{2}/i', new DurationRange
            ],
        ]);
        
        $calendar_main_setting = \Setting::of(SettingsKeys::ADMINS_BOOKING_CALENDAR_MAIN)->getOrPlaceholder();
        $book_on_datetime = $validated['date'] . " " . $validated['time'] . ":00";
        
        // if(\Suspension::isSuspendedOnDate($hall, $book_on_datetime))
        //     return response()->json([
        //         'error' => 'Hall is suspended on ' . $book_on_datetime
        //     ]);
        // 
        // if(\Suspension::isSuspendedOnDate($worker, $book_on_datetime))
        //     return response()->json([
        //         'error' => 'Worker is suspended on ' . $book_on_datetime
        //     ]);
        
        // $all_holidays = \Holiday::getAllAsUniqueDateValue($hall, $worker);
        // $for_holiday_check_val = str_replace('-', '_', $validated['date']);
        // if(in_array($for_holiday_check_val, $all_holidays))
        //     return response()->json([
        //         'error' => 'You trying book on date wich is holiday right now.'
        //     ]);
        
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
        
        
        // $duration_in_minutes = \Time::parseStringHourMinutesToMinutes($validated['duration']);
        // $start = $book_on_datetime;
        // $carbon_time = \Carbon\Carbon::parse($book_on_datetime);
        // $carbon_time->addMinutes($duration_in_minutes);
        // $end = $carbon_time->format('Y-m-d H:i:s');
        // 
        // $bookings = \Getter::of(GetterKeys::BOOKINGS)->all(
        //     new Range($start, $end, 'month'),
        //     [
        //         Params::ONLY_APPROVED => true,
        //         Params::FIRST_ITEMS => true,
        //         Params::EXCLUDE_RANGE_START_AND_END_DATES => true,
        //     ]
        // );
        // 
        // if(!empty($bookings))
        //     return response()->json([
        //         'error' => 'Wrong time, time is already taken!'
        //     ]);
            
        $booking = new Booking();
        $booking->user_id = auth()->user()->id;
        $booking->hall_id = $hall->id;
        $booking->template_id = $template->id;
        $booking->worker_id = $worker->id;
        $booking->client_id = $client->id;
        $booking->time = $book_on_datetime;
        $booking->approved = 1;
        $booking->custom_duration = \Time::parseStringHourMinutesToMinutes($validated['duration']);
        
        $check_params = [];
        if(!empty($calendar_main_setting[AdminsBookingCalendarMainSettingKeys::ENABLE_BOOKING_ON_ANY_TIME]) &&
        $calendar_main_setting[AdminsBookingCalendarMainSettingKeys::ENABLE_BOOKING_ON_ANY_TIME] === true){
            $check_params = ['crossing_other_bookings'];
        }
        
        if(!$booking->isFitsInTime($check_params))
            return response()->json([
                // 'error' => 'Booking, not fits in time!'
                'error' => $booking->getNotFitsInTimeReason()
            ]);
        
        $booking->save();
        // $booking = Booking::create([
        //     'user_id' => auth()->user()->id,
        //     'hall_id' => $hall->id,
        //     'template_id' => $template->id,
        //     'worker_id' => $worker->id,
        //     'client_id' => $client->id,
        //     'time' => $book_on_datetime,
        //     'approved' => 1,
        //     'custom_duration' => \Time::parseStringHourMinutesToMinutes($validated['duration']),
        // ]);
        
        return response()->json([
            'status' => 'success'
        ]);
    }
    
    // public function edit(Request $request, Booking $booking){
    //     // return response()->json([
    //     //     'msg' => 'test'
    //     // ]);
    // 
    //     $has_item = $request->has('hall') || $request->has('worker') || $request->has('template') ? true : false;
    //     $item_required = $has_item ? 'required' : 'nullable';
    //     $validated = $request->validate([
    //         'hall' => "{$item_required}|integer|exists:halls,id",
    //         'worker' => "{$item_required}|integer|exists:workers,id",
    //         'template' => "{$item_required}|integer|exists:templates,id",
    //         'time' => "{$item_required}|string|date_format:Y-m-d H:i:s",
    //         'duration' => 'nullable|string|max:10|regex:/\d{2}:\d{2}/i',
    //     ]);
    // 
    //     // To be shure that all parameters connected between each other
    //     if($has_item){
    //         $template = \Getter::templates()->get([
    //             TemplateParams::ID => $validated['template'],
    //             TemplateParams::WORKER_ID => $validated['worker'],
    //             TemplateParams::HALL_ID => $validated['hall'],
    //         ]);
    // 
    //         // var_dump($template->toArray());
    //         // die();
    // 
    //         if($template->isEmpty())
    //             return response()->json([
    //                 'msg' => 'wrong parameters'
    //             ]);
    //     }
    // 
    //     // die();
    // 
    //     if($has_item){
    //         $booking->hall_id = $validated['hall'];
    //         $booking->worker_id = $validated['worker'];
    //         $booking->template_id = $validated['template'];
    //     }
    // 
    //     if(!empty($validated['time']))
    //         $booking->time = $validated['time'];
    // 
    //     if(!empty($validated['duration'])){
    //         $duration_in_minutes = \Time::parseStringHourMinutesToMinutes($validated['duration']);
    //         $start = $booking->time;
    //         $carbon_time = \Carbon\Carbon::parse($booking->time);
    //         $carbon_time->addMinutes($duration_in_minutes);
    //         $end = $carbon_time->format('Y-m-d H:i:s');
    // 
    //         $bookings_getter_params = [
    //             Params::ONLY_APPROVED => true,
    //             Params::EXCLUDE_IDS => [$booking->id],
    //             Params::FIRST_ITEMS => true,
    //             Params::EXCLUDE_RANGE_START_AND_END_DATES => true,
    //         ];
    // 
    //         if(!empty($validated['worker']) && is_numeric($validated['worker']))
    //             $bookings_getter_params[Params::WORKER] = (int)$validated['worker'];
    // 
    //         $bookings = \Getter::of(GetterKeys::BOOKINGS)->all(
    //             new Range($start, $end, 'month'), $bookings_getter_params
    //         );
    // 
    //         // var_dump($bookings);
    //         // die();
    // 
    //         if(!empty($bookings))
    //             return response()->json([
    //                 'msg' => 'wrong parameters'
    //             ]);
    // 
    //         $booking->custom_duration = $duration_in_minutes;
    //     }
    // 
    //     if($booking->approved != 1)
    //         $booking->approved = 1;
    // 
    //     $booking->save();
    // 
    //     return response()->json([
    //         'status' => 'success',
    //         'booking' => $booking,
    //     ]);
    // }
    
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
        
        $calendar_main_setting = \Setting::of(SettingsKeys::ADMINS_BOOKING_CALENDAR_MAIN)->getOrPlaceholder();
        $check_params = [];
        if(!empty($calendar_main_setting[AdminsBookingCalendarMainSettingKeys::ENABLE_BOOKING_ON_ANY_TIME]) &&
        $calendar_main_setting[AdminsBookingCalendarMainSettingKeys::ENABLE_BOOKING_ON_ANY_TIME] === true)
            $check_params = ['crossing_other_bookings'];
        
        if(!$has_item){
            if(empty($validated['duration']))
                return response()->json([
                    'msg' => 'No parameters. There is nothing to do.'
                ]);
            $booking->custom_duration = \Time::parseStringHourMinutesToMinutes($validated['duration']);
            if($booking->isFitsInTime($check_params) && $booking->save())
                return response()->json([
                    'status' => 'success',
                    'booking' => $booking,
                ]);
            return response()->json([
                'msg' => $booking->getNotFitsInTimeReason(),
            ]);
        }
        
        // To be shure that all parameters connected between each other
        $template = \Getter::templates()->get([
            TemplateParams::ID => $validated['template'],
            TemplateParams::WORKER_ID => $validated['worker'],
            TemplateParams::HALL_ID => $validated['hall'],
        ]);
        
        // var_dump($template->toArray());
        // die();
        
        if($template->isEmpty())
            return response()->json([
                'msg' => 'Wrong parameters'
            ]);
        
        $booking->hall_id = $validated['hall'];
        $booking->worker_id = $validated['worker'];
        $booking->template_id = $validated['template'];
        
        if(!empty($validated['time']))
            $booking->time = $validated['time'];
            
        if(!empty($validated['duration']))
            $booking->custom_duration = \Time::parseStringHourMinutesToMinutes($validated['duration']);
            
        if($booking->isFitsInTime($check_params) && $booking->saveAsApproved())
            return response()->json([
                'status' => 'success',
                'booking' => $booking,
            ]);
        return response()->json([
            'msg' => $booking->getNotFitsInTimeReason()
        ]);
    }
    
    // protected function isBookingModelFits(Booking $booking){
    //     $duration = empty($booking->custom_duration) ? $booking->custom_duration : $booking->right_duration;
    //     // $duration_in_minutes = \Time::parseStringHourMinutesToMinutes($validated['duration']);
    //     $start = $booking->time;
    //     $carbon_time = \Carbon\Carbon::parse($booking->time);
    //     $carbon_time->addMinutes($duration);
    //     $end = $carbon_time->format('Y-m-d H:i:s');
    // 
    //     $bookings_getter_params = [
    //         Params::ONLY_APPROVED => true,
    //         Params::EXCLUDE_IDS => [$booking->id],
    //         Params::FIRST_ITEMS => true,
    //         Params::EXCLUDE_RANGE_START_AND_END_DATES => true,
    //         Params::WORKER => $booking->worker_id
    //     ];
    // 
    //     $bookings = \Getter::bookings()->all(
    //         new Range($start, $end, 'month'), $bookings_getter_params
    //     );
    // 
    //     return !empty($bookings);
    // }
    
    protected function isDateBookable(string $date, int $hall_id, int $worker_id){
        
    }
    
}
