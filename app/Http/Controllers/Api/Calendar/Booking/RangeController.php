<?php

namespace App\Http\Controllers\Api\Calendar\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Exceptions\Api\Calendar\BadRangeException;

class RangeController extends Controller{
    
    public function index(Request $request, $user_id, $start, $end){
        if(!($user = User::find($user_id)))
            abort(404, 'Page not found');
        
        // echo $this->auth->user()->timezone;
        echo date('Y-m-d H:i:s', 1618040400);
        die();
        
        $start_timestamp = strtotime($start);
        $end_timestamp = strtotime($end);
        
        // return response()->json([$start, $end]);
        // dd($start_timestamp, $end_timestamp);
        
        if($start_timestamp > $end_timestamp)
            throw new BadRangeException();
        
        $start_date = date('Y-m-d', $start_timestamp);
        $end_date = date('Y-m-d', $end_timestamp);
        
        $model = Booking::user($user_id)->where('time', '>=', $start_date)
            ->where('time', '<=', $end_date)
            ->orderBy('time', 'ASC');
            
        if($request->exists('hall'))
            $model->where('hall_id', '=', $request->hall);
            
        if($request->exists('worker'))
            $model->where('worker_id', '=', $request->worker);
        
        if($request->exists('template'))
            $model->where('template_id', '=', $request->template);
            
        $bookings = $model->get();
        
        $hall_start = '08:00';
        $hall_end = '20:00';
        
        $hall_end_arr = explode(':', $hall_end);
        
        // var_dump($bookings->toArray());
        // die();
        
        $start_date_carbon = \Carbon\Carbon::parse($start_date);
        $end_date_carbon = \Carbon\Carbon::parse($end_date);
        
        $output = [];
        do{
            if((int)$start_date_carbon->format("w") == 0){
                $weekday = 7;
            }else{
                $weekday = (int)$start_date_carbon->format("w");
            }
            
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
            if(!$bookings->isEmpty()){
                // dd(111);
                $booked = [];
                $free = [];
                foreach($bookings->toArray() as $booking){
                    $time = \Carbon\Carbon::parse($booking['time'])->format('Y-m-d');
                    // var_dump($time, ($itm['year'] . '-' . $itm['month'] . '-' . $itm['day']));
                    // die();
                    if($time == ($itm['year'] . '-' . $itm['month'] . '-' . $itm['day']))
                        $booked[] = $booking;
                }
                // var_dump($booked);
                // die();
                if(!empty($booked)){
                    // var_dump($booked);
                    // die();
                    $start = $hall_start;
                    // $end = $hall_end;
                    $end = \Carbon\Carbon::parse(($itm['year'] . '-' . $itm['month'] . '-' . $itm['day']) . ' ' . $hall_end);
                    foreach($booked as $v){
                        $time = \Carbon\Carbon::parse($v['time']);
                        $start = \Carbon\Carbon::parse(($itm['year'] . '-' . $itm['month'] . '-' . $itm['day']) . ' ' . $start);
                        // var_dump($booked);
                        // var_dump($start->lt($time));
                        // var_dump(($itm['year'] . '-' . $itm['month'] . '-' . $itm['day']) . ' ' . $hall_start);
                        // var_dump($time);
                        // die();
                        if($start->lt($time)){
                            $free[] = [
                                'from' => $start->format('H:i'),
                                'to' => $time->format('H:i'),
                            ];
                            // $free[] = [
                            //     'from' => $time->format('H:i:s'),
                            //     'to' => $time->addHours(1)->addMinutes(15)->format('H:i:s')
                            // ];
                            $start = $time->addHours(1)->addMinutes(15)->format('H:i:s');
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
    
    protected function getTemplateUrl($current_url){
        // $current_url = Request::url();
        $current_url_arr = explode('/', $current_url);
        array_pop($current_url_arr); array_pop($current_url_arr);
        $current_url_str_composed = implode('/', $current_url_arr);
        $current_url_str_composed .= '/dd-mm-yyyy/dd-mm-yyyy';
        return $current_url_str_composed;
    }
    
}
