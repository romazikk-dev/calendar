<?php

namespace App\Http\Controllers\Api\Calendar\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Hall;
use App\Models\Worker;
use App\Models\Template;
use App\Exceptions\Api\Calendar\BadRangeException;
use App\Classes\Range\Range;
use App\Classes\Booking\BookingRetrievial;
use App\Scopes\UserScope;
use App\Classes\Getter\Booking\Enums\Params as BookingGetterParams;

class RangeController extends Controller{
    
    public function client(Request $request, User $user, $start, $end){
        // var_dump([$start, $end]);
        // die();
        
        // $res = Worker::withoutGlobalScope(UserScope::class)->where('id', $request->worker)->first();
        // var_dump($res);
        // die();
        
        // $worker = \Getter::workers()->get([
        //     'id' => $request->worker,
        //     'owner_id' => $user->id,
        // ]);
        // var_dump($worker);
        // die();
        
        
        // $range = new Range();
        // var_dump($range->index());
        // die();
        
        // if(!($user = User::find($user_id)))
        //     abort(404, 'Page not found');
            
        $validated = $request->validate([
            'hall' => 'required|integer|exists:halls,id',
            'worker' => 'required|integer|exists:workers,id',
            'template' => 'required|integer|exists:templates,id',
            // 'view' => 'required|string|in:month,week,day,list',
        ]);
        
        // $hall = Hall::find($validated['hall']);
        $hall = Hall::withoutGlobalScope(UserScope::class)
            ->byUser($user->id)
            ->where('id', $request->hall)
            ->first();
        
        // var_dump($hall);
        // var_dump($validated['hall']);
        // die();
        
        // var_dump(auth()->user()->id);
        // var_dump([
        //     BookingGetterParams::WORKER => $validated['worker'],
        //     BookingGetterParams::OWNER => $user->id,
        //     BookingGetterParams::CLIENT => auth()->user()->id,
        //     BookingGetterParams::WITH_EVENTS_PER_CLIENT => auth()->user()->id,
        //     BookingGetterParams::WITH => [
        //         // 'templateWithoutUserScope'
        //         'templateWithoutUserScope.specific', 'workerWithoutUserScope',
        //         'hallWithoutUserScope', 'clientWithoutUserScope'
        //     ],
        // ]);
        // die();
        
        // var_dump($user->id);
        // die();
        
        // var_dump(BookingGetterParams::all());
        $params = [
            BookingGetterParams::WORKER => $validated['worker'],
            BookingGetterParams::OWNER => $user->id,
            BookingGetterParams::CLIENT => auth()->user()->id,
            BookingGetterParams::FREE_WITH_EVENTS => true,
            BookingGetterParams::WITH => [
                // 'templateWithoutUserScope'
                'templateWithoutUserScope.specific', 'workerWithoutUserScope',
                'hallWithoutUserScope', 'clientWithoutUserScope'
            ],
        ];
        
        // var_dump($params);
        // die();
        
        $slots = \Getter::bookings()->free(
            new Range($start, $end, 'month'), $params
        );
        
        // var_dump($slots);
        // die();
        
        $output = [
            'data' => $slots,
            'business_hours' => json_decode($hall->business_hours, true),
            // 'business_hours' => $bookingRetrievial->getBusinessHours(true, true),
        ];
        
        if ($request->wantsJson()) {
            return response()->json($output);
        } else {
            return view('api.calendar.booking.range.index', [
                'range' => $output,
                // 'template_url' => $this->getTemplateUrl($request->url()),
                // 't' => 'test',
            ]);
        }
        
        // var_dump($slots);
        // echo 1111;
        die();
        
        // $hall_start = '08:00';
        // $hall_end = '22:00';
        
        // echo $this->auth->user()->timezone;
        // echo date('Y-m-d H:i:s', 1618040400);
        // die();
        
        $hall = Hall::withoutGlobalScope(UserScope::class)->find($validated['hall']);
        $worker = Worker::withoutGlobalScope(UserScope::class)->find($validated['worker']);
        $template = Template::withoutGlobalScope(UserScope::class)->find($validated['template']);
        $client = $request->user();
        
        $range = new Range($start, $end, $validated['view']);
        $bookingRetrievial = new BookingRetrievial($user, $hall, $worker, $template, $range, $client);
        
        $free_slots = $bookingRetrievial->getFreeSlots();
        
        $output = [
            'data' => $free_slots,
            // 'business_hours' => json_decode($hall->business_hours, true),
            'business_hours' => $bookingRetrievial->getBusinessHours(true, true),
        ];
        
        // var_dump(json_decode($hall->business_hours, true));
        // die();
        
        if ($request->wantsJson()) {
            return response()->json($output);
        } else {
            return view('api.calendar.booking.range.index', [
                'range' => $free_slots,
                // 'template_url' => $this->getTemplateUrl($request->url()),
                // 't' => 'test',
            ]);
        }
        // var_dump($free_slots);
        // die();
    }
    
    public function guest(Request $request, User $user, $start, $end){
        
        $validated = $request->validate([
            'hall' => 'required|integer|exists:halls,id',
            'worker' => 'required|integer|exists:workers,id',
            'template' => 'required|integer|exists:templates,id',
            // 'view' => 'required|string|in:month,week,day,list',
        ]);
        
        // $params[Params::WITH] = [
        //     'templateWithoutUserScope.specific', 'workerWithoutUserScope',
        //     'hallWithoutUserScope', 'clientWithoutUserScope'
        // ];
        
        $slots = \Getter::bookings()->free(
            new Range($start, $end, 'month'), [
                BookingGetterParams::WORKER => $validated['worker'],
                BookingGetterParams::OWNER => $user->id,
                // BookingGetterParams::CLIENT => auth()->user()->id,
                // BookingGetterParams::WITH_EVENTS_PER_CLIENT => auth()->user()->id,
                BookingGetterParams::WITH => [
                    'templateWithoutUserScope'
                    // 'templateWithoutUserScope.specific', 'workerWithoutUserScope',
                    // 'hallWithoutUserScope', 'clientWithoutUserScope'
                ],
            ]
        );
        
        $hall = Hall::withoutGlobalScope(UserScope::class)->find($validated['hall']);
        
        $output = [
            'data' => $slots,
            'business_hours' => json_decode($hall->business_hours, true),
            // 'business_hours' => $bookingRetrievial->getBusinessHours(true, true),
        ];
        
        return response()->json($output);
        
        // if ($request->wantsJson()) {
        //     return response()->json($output);
        // } else {
        //     return view('api.calendar.booking.range.index', [
        //         'range' => $output,
        //         // 'template_url' => $this->getTemplateUrl($request->url()),
        //         // 't' => 'test',
        //     ]);
        // }
        
        // $hall_start = '08:00';
        // $hall_end = '22:00';
        
        // echo $this->auth->user()->timezone;
        // echo date('Y-m-d H:i:s', 1618040400);
        // die();
        
        $hall = Hall::withoutGlobalScope(UserScope::class)->find($validated['hall']);
        $worker = Worker::withoutGlobalScope(UserScope::class)->find($validated['worker']);
        $template = Template::withoutGlobalScope(UserScope::class)->find($validated['template']);
        // $client = $request->user();
        
        $range = new Range($start, $end, $validated['view']);
        $bookingRetrievial = new BookingRetrievial($user, $hall, $worker, $template, $range);
        
        // var_dump(1); die();
        
        $free_slots = $bookingRetrievial->getFreeSlots();
        
        // var_dump($bookingRetrievial->getBusinessHours(true, true));
        // die();
        
        $output = [
            'data' => $free_slots,
            // 'business_hours' => json_decode($hall->business_hours, true),
            'business_hours' => $bookingRetrievial->getBusinessHours(true, true),
        ];
        
        // var_dump(json_decode($hall->business_hours, true));
        // die();
        
        if ($request->wantsJson()) {
            return response()->json($output);
        } else {
            return view('api.calendar.booking.range.index', [
                'range' => $free_slots,
                // 'template_url' => $this->getTemplateUrl($request->url()),
                // 't' => 'test',
            ]);
        }
        // var_dump($free_slots);
        // die();
    }
    
    // public function client(Request $request, User $user, $start, $end){
    //     var_dump([$start, $end]);
    //     die();
    // 
    //     // $range = new Range();
    //     // var_dump($range->index());
    //     // die();
    // 
    //     // if(!($user = User::find($user_id)))
    //     //     abort(404, 'Page not found');
    // 
    //     $validated = $request->validate([
    //         'hall' => 'required|integer|exists:halls,id',
    //         'worker' => 'required|integer|exists:workers,id',
    //         'template' => 'required|integer|exists:templates,id',
    //         'view' => 'required|string|in:month,week,day,list',
    //     ]);
    // 
    //     // $hall_start = '08:00';
    //     // $hall_end = '22:00';
    // 
    //     // echo $this->auth->user()->timezone;
    //     // echo date('Y-m-d H:i:s', 1618040400);
    //     // die();
    // 
    //     $hall = Hall::withoutGlobalScope(UserScope::class)->find($validated['hall']);
    //     $worker = Worker::withoutGlobalScope(UserScope::class)->find($validated['worker']);
    //     $template = Template::withoutGlobalScope(UserScope::class)->find($validated['template']);
    //     $client = $request->user();
    // 
    //     $range = new Range($start, $end, $validated['view']);
    //     $bookingRetrievial = new BookingRetrievial($user, $hall, $worker, $template, $range, $client);
    // 
    //     $free_slots = $bookingRetrievial->getFreeSlots();
    // 
    //     $output = [
    //         'data' => $free_slots,
    //         // 'business_hours' => json_decode($hall->business_hours, true),
    //         'business_hours' => $bookingRetrievial->getBusinessHours(true, true),
    //     ];
    // 
    //     // var_dump(json_decode($hall->business_hours, true));
    //     // die();
    // 
    //     if ($request->wantsJson()) {
    //         return response()->json($output);
    //     } else {
    //         return view('api.calendar.booking.range.index', [
    //             'range' => $free_slots,
    //             // 'template_url' => $this->getTemplateUrl($request->url()),
    //             // 't' => 'test',
    //         ]);
    //     }
    //     // var_dump($free_slots);
    //     // die();
    // }
    
    
    // public function guest(Request $request, User $user, $start, $end){
    //     // var_dump([$user_id, $start, $end]);
    //     // die();
    // 
    //     // $range = new Range();
    //     // var_dump($range->index());
    //     // die();
    // 
    //     // if(!($user = User::find($user_id)))
    //     //     abort(404, 'Page not found');
    // 
    //     $validated = $request->validate([
    //         'hall' => 'required|integer|exists:halls,id',
    //         'worker' => 'required|integer|exists:workers,id',
    //         'template' => 'required|integer|exists:templates,id',
    //         'view' => 'required|string|in:month,week,day,list',
    //     ]);
    // 
    //     // $hall_start = '08:00';
    //     // $hall_end = '22:00';
    // 
    //     // echo $this->auth->user()->timezone;
    //     // echo date('Y-m-d H:i:s', 1618040400);
    //     // die();
    // 
    //     $hall = Hall::withoutGlobalScope(UserScope::class)->find($validated['hall']);
    //     $worker = Worker::withoutGlobalScope(UserScope::class)->find($validated['worker']);
    //     $template = Template::withoutGlobalScope(UserScope::class)->find($validated['template']);
    //     // $client = $request->user();
    // 
    //     $range = new Range($start, $end, $validated['view']);
    //     $bookingRetrievial = new BookingRetrievial($user, $hall, $worker, $template, $range);
    // 
    //     // var_dump(1); die();
    // 
    //     $free_slots = $bookingRetrievial->getFreeSlots();
    // 
    //     // var_dump($bookingRetrievial->getBusinessHours(true, true));
    //     // die();
    // 
    //     $output = [
    //         'data' => $free_slots,
    //         // 'business_hours' => json_decode($hall->business_hours, true),
    //         'business_hours' => $bookingRetrievial->getBusinessHours(true, true),
    //     ];
    // 
    //     // var_dump(json_decode($hall->business_hours, true));
    //     // die();
    // 
    //     if ($request->wantsJson()) {
    //         return response()->json($output);
    //     } else {
    //         return view('api.calendar.booking.range.index', [
    //             'range' => $free_slots,
    //             // 'template_url' => $this->getTemplateUrl($request->url()),
    //             // 't' => 'test',
    //         ]);
    //     }
    //     // var_dump($free_slots);
    //     // die();
    // }
    
    // protected function wrongTimestamps(){
    //     return (is_null($this->start_timestamp) || is_null($this->end_timestamp) ||
    //     $this->start_timestamp > $this->end_timestamp);
    // }
    // 
    // protected function setStartEndDates(){
    //     $this->start_date = date('Y-m-d', $this->start_timestamp);
    //     $this->end_date = date('Y-m-d', $this->end_timestamp);
    // }
    
    // public function index(Request $request, User $user, $start, $end){
    //     // var_dump([$user_id, $start, $end]);
    //     // die();
    // 
    //     // $range = new Range();
    //     // var_dump($range->index());
    //     // die();
    // 
    //     // if(!($user = User::find($user_id)))
    //     //     abort(404, 'Page not found');
    // 
    //     $validated = $request->validate([
    //         'hall' => 'required|integer|exists:halls,id',
    //         'worker' => 'required|integer|exists:workers,id',
    //         'template' => 'required|integer|exists:templates,id',
    //         'view' => 'required|string|in:month,week,day,list',
    //     ]);
    // 
    //     // $hall_start = '08:00';
    //     // $hall_end = '22:00';
    // 
    //     // echo $this->auth->user()->timezone;
    //     // echo date('Y-m-d H:i:s', 1618040400);
    //     // die();
    // 
    //     $hall = Hall::withoutGlobalScope(UserScope::class)->find($validated['hall']);
    //     $worker = Worker::withoutGlobalScope(UserScope::class)->find($validated['worker']);
    //     $template = Template::withoutGlobalScope(UserScope::class)->find($validated['template']);
    // 
    //     $range = new Range($start, $end, $validated['view']);
    //     $bookingRetrievial = new BookingRetrievial($user, $hall, $worker, $template, $range);
    // 
    //     $free_slots = $bookingRetrievial->getFreeSlots();
    // 
    //     $output = [
    //         'data' => $free_slots,
    //         'business_hours' => json_decode($hall->business_hours, true),
    //     ];
    // 
    //     // var_dump(json_decode($hall->business_hours, true));
    //     // die();
    // 
    //     if ($request->wantsJson()) {
    //         return response()->json($output);
    //     } else {
    //         return view('api.calendar.booking.range.index', [
    //             'range' => $free_slots,
    //             // 'template_url' => $this->getTemplateUrl($request->url()),
    //             // 't' => 'test',
    //         ]);
    //     }
    //     // var_dump($free_slots);
    //     // die();
    // }
    
    public function indexxxdd(Request $request, $user_id, $start, $end){
        // var_dump([$user_id, $start, $end]);
        // die();
        
        // $range = new Range();
        // var_dump($range->index());
        // die();
        
        if(!($user = User::find($user_id)))
            abort(404, 'Page not found');
            
        $validated = $request->validate([
            'hall' => 'required|integer|exists:halls,id',
            'worker' => 'required|integer|exists:workers,id',
            'template' => 'required|integer|exists:templates,id',
            'view' => 'required|string|in:month,week,day',
        ]);
        
        $hall_start = '08:00';
        $hall_end = '20:00';
        
        // echo $this->auth->user()->timezone;
        // echo date('Y-m-d H:i:s', 1618040400);
        // die();
        
        $bookings = (new Range(
            $user,
            $validated['hall'],
            $validated['worker'],
            $validated['template'],
            $validated['view'],
            $start,
            $end
        ))->getDateTimeKeyArray();
        
        var_dump($bookings);
        die();
        
        // $this->start_timestamp = strtotime($start);
        // $this->end_timestamp = strtotime($end);
        // unset($start, $end);
        
        // return response()->json([$start, $end]);
        // dd($start_timestamp, $end_timestamp);
        
        // if($this->wrongTimestamps())
        //     throw new BadRangeException();
        // if($start_timestamp > $end_timestamp)
        //     throw new BadRangeException();
        
        // $this->setStartEndDates();
        // $start_date = date('Y-m-d', $start_timestamp);
        // $end_date = date('Y-m-d', $end_timestamp);
        
        // var_dump([$start_date, $end_date]);
        // die();
        
        // $model = Booking::user($user_id)->where('time', '>=', $start_date)
        //     ->where('time', '<=', $end_date)
        //     ->orderBy('time', 'ASC');
        // 
        // if($request->exists('hall'))
        //     $model->where('hall_id', '=', $request->hall);
        // 
        // if($request->exists('worker'))
        //     $model->where('worker_id', '=', $request->worker);
        // 
        // if($request->exists('template'))
        //     $model->where('template_id', '=', $request->template);
        // 
        // $bookings = $model->get();
        
        // Set book items as indexed array: DATE => TIME => BOOKING_ITEM
        // $booked_itms = [];
        // foreach($bookings as $booking){
        //     // $booking_date = \Carbon\Carbon::parse($booking['time'])->format('Y-m-d');
        //     $booking_date_index = \Carbon\Carbon::parse($booking['time'])->format('Y_m_d');
        //     $booking_hour_index = \Carbon\Carbon::parse($booking['time'])->format('H_i');
        //     $booked_itms[$booking_date_index][$booking_hour_index] = $booking;
        // }
        
        // var_dump($booked_itms);
        // die();
        
        // $hall_start = '08:00';
        // $hall_end = '20:00';
        
        $hall_end_arr = explode(':', $hall_end);
        
        // var_dump($bookings->toArray());
        // die();
        
        // var_dump([$start_date, $end_date]);
        // die();
        
        $start_date_carbon = \Carbon\Carbon::parse($start_date);
        $end_date_carbon = \Carbon\Carbon::parse($end_date);
        
        // var_dump([$start_date_carbon->format('Y-m-d H:i:s')]);
        // var_dump([$end_date_carbon->format('Y-m-d H:i:s')]);
        // die();
        
        $output = [];
        do{
            //set weekday 1-monday|7-sunday
            if((int)$start_date_carbon->format("w") == 0){
                $weekday = 7;
            }else{
                $weekday = (int)$start_date_carbon->format("w");
            }
            
            //Set initial itm
            $itm = [
                'year' => $start_date_carbon->format("Y"),
                'month' => $start_date_carbon->format("m"),
                'day' => $start_date_carbon->format("d"),
                'weekday' => $weekday,
                'bookable' => true,
                // 'bookable' => false,
                'free' => [
                    [
                        'from' => $hall_start,
                        'to' => $hall_end
                    ],
                    // [
                    //     'from' => '08:40:00',
                    //     'to' => '11:30:00'
                    // ],
                ],
            ];
            
            $itm_date_index = $itm['year'] . '_' . $itm['month'] . '_' . $itm['day'];
            
            // $booked_itms
            if(!empty($booked_itms[$itm_date_index])){
            // if(!$bookings->isEmpty()){
                $booked = $booked_itms[$itm_date_index];
                
                $itm_date = $itm['year'] . '-' . $itm['month'] . '-' . $itm['day'];
                
                $itm_hall_start_datetime = $itm_date . ' ' . $hall_start;
                if(count(explode(':', $hall_start)) <= 2)
                    $itm_hall_start_datetime .= ':00';
                
                $itm_hall_end_datetime = $itm_date . ' ' . $hall_end;
                if(count(explode(':', $hall_end)) <= 2)
                    $itm_hall_end_datetime .= ':00';
                
                // $itm = $this->composeItmWithFreeItms($itm, $bookings);
                // dd(111);
                // $booked = [];
                // $free = [];
                // foreach($bookings->toArray() as &$booking){
                //     $booking_date = \Carbon\Carbon::parse($booking['time'])->format('Y-m-d');
                //     // var_dump([$booking_date, $itm_date]);
                //     // var_dump($itm_date);
                //     // die();
                //     if($booking_date == $itm_date){
                //         $booked[] = $booking;
                //         // unset($booking);
                //     }
                // }
                // var_dump($booked);
                // die();
                // if(!empty($booked)){
                    // var_dump($booked);
                    // die();
                    // $start = $hall_start;
                    $start_carbon = \Carbon\Carbon::parse($itm_hall_start_datetime);
                    // $end = $hall_end;
                    $end_carbon = \Carbon\Carbon::parse($itm_hall_start_datetime);
                
                    foreach($booked as $k=>$v){
                        $booked_datetime_carbon = \Carbon\Carbon::parse($v['time']);
                
                        // var_dump(($itm['year'] . '-' . $itm['month'] . '-' . $itm['day']) . ' ' . $start);
                        // die();
                
                        // $itmm = $itm;
                        
                        var_dump($itm['year'] . '-' . $itm['month'] . '-' . $itm['day'] . ' ' . $start);
                        die();
                        
                        $start_datetime_carbon = \Carbon\Carbon::parse($itm['year'] . '-' . $itm['month'] . '-' . $itm['day'] . ' ' . $start);
                
                        // var_dump($itm['year']);
                        // var_dump($itm['year'] . '-' . $itm['month'] . '-' . $itm['day'] . ' ' . $start);
                        // die();
                
                        // var_dump($booked);
                        // var_dump($start->lt($time));
                        // var_dump(($itm['year'] . '-' . $itm['month'] . '-' . $itm['day']) . ' ' . $hall_start);
                        // var_dump($time);
                        // die();
                        if($start_datetime_carbon->lt($booked_datetime_carbon)){
                            $free[] = [
                                'from' => $start_datetime_carbon->format('H:i'),
                                'to' => $booked_datetime_carbon->format('H:i'),
                            ];
                            // $free[] = [
                            //     'from' => $time->format('H:i:s'),
                            //     'to' => $time->addHours(1)->addMinutes(15)->format('H:i:s')
                            // ];
                            $template = $v->template;
                            dd(111);
                            $start = $time_carbon->addHours(1)->addMinutes(15)->format('H:i:s');
                            // $start = $time->format('H:i:s');
                        }
                
                        // $time_arr = explode(':', $time);
                        // $hall_start_arr = explode(':', $hall_start);
                        // if($time_arr[0] > $hall_start_arr[0]){
                        //     $free[] = [
                        //         'from' => $hall_start_arr[0],
                        //         'to' => $time_arr[0]
                        //     ];
                        //     $hall_start = 
                        // }
                        // var_dump($time);
                        // die();
                    }
                
                    $start = \Carbon\Carbon::parse(($itm['year'] . '-' . $itm['month'] . '-' . $itm['day']) . ' ' . $start);
                    if($start->lt($end)){
                        $free[] = [
                            'from' => $start->format('H:i'),
                            'to' => $end->format('H:i'),
                        ];
                    }
                // }
                
                // var_dump(1111);
                // var_dump($free);
                // die();
                
                if(!empty($free))
                    $itm['free'] = $free;
                    
                // if(!empty($booked)){
                //     // var_dump(111);
                //     var_dump($free);
                //     die();
                // }
                // var_dump(111);
                // var_dump($bookings);
                // die();
            }
            $output[] = $itm;
            $start_date_carbon->addDays(1);
        }while($start_date_carbon->lte($end_date_carbon));
        
        // $start_date_carbon->addDays(1);
        // dump($start_date_carbon->diffInDays($end_date_carbon));
        // $start_date_carbon->addDays(39);
        // dump($start_date_carbon->gte($end_date_carbon));
        // dd($start_date_carbon->format("Y-m-d"));
        
        // $bookings_by_range = Booking::where('time', '>=', $start_date)->where('time', '<=', $end_date)->get();
        
        // dd($output);
        
        // dd($bookings_by_range);
        if ($request->wantsJson()) {
            return response()->json([
                'start' => $hall_start,
                'end' => $hall_end,
                'data' => $output
            ]);
        } else {
            return view('api.calendar.booking.range.index', [
                'range' => $output,
                'template_url' => $this->getTemplateUrl($request->url()),
                // 't' => 'test',
            ]);
        }
    }
    
    protected function composeItmWithFreeItms(array $itm, Booking $bookings){
        
        $itm_date = $itm['year'] . '-' . $itm['month'] . '-' . $itm['day'];
        
        $itm_hall_start_datetime = $itm_date . ' ' . $hall_start;
        if(count(explode(':', $hall_start)) <= 2)
            $itm_hall_start_datetime .= ':00';
            
        $itm_hall_end_datetime = $itm_date . ' ' . $hall_end;
        if(count(explode(':', $hall_end)) <= 2)
            $itm_hall_end_datetime .= ':00';
            
        // dd(111);
        $booked = [];
        $free = [];
        
        foreach($bookings->toArray() as $booking){
            $booking_date = \Carbon\Carbon::parse($booking['time'])->format('Y-m-d');
            // var_dump([$time, $booking['time']]);
            // var_dump($itm_date);
            // die();
            if($booking_date == $itm_date)
                $booked[] = $booking;
        }
        
        // var_dump($booked);
        // die();
        if(!empty($booked)){
            // var_dump($booked);
            // die();
            // $start = $hall_start;
            $start = \Carbon\Carbon::parse($itm_hall_start_datetime);
            // $end = $hall_end;
            $end = \Carbon\Carbon::parse($itm_hall_start_datetime);
            
            foreach($booked as $v){
                $time_carbon = \Carbon\Carbon::parse($v['time']);
                
                // var_dump(($itm['year'] . '-' . $itm['month'] . '-' . $itm['day']) . ' ' . $start);
                // die();
                
                // $itmm = $itm;
                
                $start_carbon = \Carbon\Carbon::parse($itm['year'] . '-' . $itm['month'] . '-' . $itm['day'] . ' ' . $start);
                
                // var_dump($itm['year']);
                // var_dump($itm['year'] . '-' . $itm['month'] . '-' . $itm['day'] . ' ' . $start);
                // die();
                
                // var_dump($booked);
                // var_dump($start->lt($time));
                // var_dump(($itm['year'] . '-' . $itm['month'] . '-' . $itm['day']) . ' ' . $hall_start);
                // var_dump($time);
                // die();
                if($start_carbon->lt($time_carbon)){
                    $free[] = [
                        'from' => $start_carbon->format('H:i'),
                        'to' => $time_carbon->format('H:i'),
                    ];
                    // $free[] = [
                    //     'from' => $time->format('H:i:s'),
                    //     'to' => $time->addHours(1)->addMinutes(15)->format('H:i:s')
                    // ];
                    $start = $time_carbon->addHours(1)->addMinutes(15)->format('H:i:s');
                    // $start = $time->format('H:i:s');
                }
                
                // $time_arr = explode(':', $time);
                // $hall_start_arr = explode(':', $hall_start);
                // if($time_arr[0] > $hall_start_arr[0]){
                //     $free[] = [
                //         'from' => $hall_start_arr[0],
                //         'to' => $time_arr[0]
                //     ];
                //     $hall_start = 
                // }
                // var_dump($time);
                // die();
            }
            
            $start = \Carbon\Carbon::parse(($itm['year'] . '-' . $itm['month'] . '-' . $itm['day']) . ' ' . $start);
            if($start->lt($end)){
                $free[] = [
                    'from' => $start->format('H:i'),
                    'to' => $end->format('H:i'),
                ];
            }
        }
        
        // var_dump(1111);
        // var_dump($free);
        // die();
        
        if(!empty($free))
            $itm['free'] = $free;
        
    }
    
    protected function getTemplateUrl($current_url){
        // $current_url = Request::url();
        $current_url_arr = explode('/', $current_url);
        array_pop($current_url_arr); array_pop($current_url_arr);
        $current_url_str_composed = implode('/', $current_url_arr);
        $current_url_str_composed .= '/dd-mm-yyyy/dd-mm-yyyy';
        return $current_url_str_composed;
    }
    
}
