<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Hall;
use App\Models\Worker;
use App\Models\Template;
use App\Models\Client;
use App\Scopes\UserScope;
use App\Models\TemplateSpecifics;
use App\Classes\Setting\Enums\Keys as SettingKeys;
use App\Classes\Getter\Enums\Keys as GetterKeys;
use App\Classes\BookedAndRequested\Retrieval as BookedAndRequestedRetrieval;
use App\Classes\Range\Range;

class BookingsController extends Controller
{
    public function index(){
        
        $admins_booking_calendar_main_settings = \Setting::of(SettingKeys::ADMINS_BOOKING_CALENDAR_MAIN)->getOrPlaceholder();
        // dd($admins_booking_calendar_main_settings);
        
        // \Time::my();
        // \Getter::of(GetterKeys::BOOKINGS)->my();
        // $range = new Range('01-06-2021', '01-07-2021', 'month');
        // // dd($range);
        // $hall = Hall::find(3);
        // $booked_and_requested_retrievial = new BookedAndRequestedRetrieval($range, [
        //     // "hall" => $hall
        // ]);
        // $booked_and_requested_retrievial->get();
        // 
        // dd(1111);
        // 
        // dd($booked_and_requested_retrievial->get());
        // // new BookedAndRequestedRetrievial();
        // dd(111);
        
        // dd(auth()->user()->id);
        $filters = !empty($_COOKIE['filters']) ? json_decode($_COOKIE['filters']) : null;
        $dashboard_calendar_move_event = !empty($_COOKIE['dashboard_calendar_move_event']) ?
            json_decode($_COOKIE['dashboard_calendar_move_event']) : null;
        $token = !empty($_COOKIE['token']) ? $_COOKIE['token'] : null;
        
        
        $moving_event = !empty($_COOKIE['moving_event']) ? json_decode($_COOKIE['moving_event'], true) : null;
        
        // dd($moving_event);
        
        // dd($dashboard_calendar_move_event);
        
        // $filters = [
        //     "hall" => 1,
        //     "worker" => 1,
        //     "template" => 1,
        // ];
        // $filters = (object) $filters;
        
        // dd($filters);
        // dd([
        //     $filters, $token
        // ]);
        
        // $owner_id = auth()->user()->id;
        // 
        // $owner = User::find($owner_id);
        // 
        // $hall_model = Hall::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id);
        
        $hall_model = Hall::query();
        
        // $hall_model = Hall::withoutGlobalScope(UserScope::class)->whereHas('workers', function($query) use ($owner){
        //     // dd(3333);
        //     return $query->withoutGlobalScope(UserScope::class)->whereHas('templates', function($query) use ($owner){
        //         // return $query->where('user_id', $user->id)
        //         //     ->where('has_paid', true);
        //     })->byUser($owner->id);
        // })->byUser($owner->id)->first();
        
        // dd($hall_model->workers[0]->templates);
        // dd($hall_model->toArray());
        
        // $worker_model = Worker::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id);
        $worker_model = Worker::query();
        $template_model = Template::query()->with('specific');
        
        $halls = $hall_model->get();
        
        // $workers = $worker_model->get();
        // $templates = $template_model->with('specific')->get();
        
        if(!empty($filters) && isset($filters->hall))
            $filtered_hall = $hall_model->where('id', '=', (int)$filters->hall)->first();
        if(!empty($filters) && isset($filters->worker))
            $filtered_worker = $worker_model->where('id', '=', (int)$filters->worker)->first();
        if(!empty($filters) && isset($filters->template))
            $filtered_template = $template_model->where('id', '=', (int)$filters->template)->first();
        if(!empty($filters) && isset($filters->view))
            $filtered_view = $filters->view;
        
        // dd($filtered_hall);
        
        $db_specifics = TemplateSpecifics::all()->toArray();
        if(!empty($db_specifics))
            $parsed_specifics = \Specifics::parseDbReesultToTreeArray($db_specifics, true);
        
        $db_specifics_arr_as_key_index = [];
        foreach($db_specifics as $k => $v)
            $db_specifics_arr_as_key_index[$v['id']] = $v;
            
        // dd($db_specifics_arr_as_key_index);
        // dd($templates[0]->specific);
        // dd($templates);
        // dd($templates->toArray());
        
        // dd(auth()->user()->toArray());
        
        $output = [
            // 'token' => $token,
            // 'owner' => auth()->user()->toArray(),
            'owner' => auth()->user(),
            'halls' => $halls->toArray(),
            'template_specifics' => !empty($parsed_specifics) ? $parsed_specifics : [],
            'template_specifics_as_id_key' => !empty($db_specifics_arr_as_key_index) ? $db_specifics_arr_as_key_index : [],
            // 'workers' => $workers->toArray(),
            // 'templates' => $templates->toArray(),
            'filters' => null,
            'moving_event' => null,
            'custom_titles' => \Setting::of(SettingKeys::CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES)->getOrPlaceholder(),
            'calendar_settings' => $admins_booking_calendar_main_settings,
        ];
        
        if(!empty($moving_event)){
            $output_moving_event = [];
            if(!empty($moving_event['event']) && is_numeric($moving_event['event'])){
                $output_moving_event['event'] = Booking::where('id', (int)$moving_event['event'])
                    ->with([
                        'templateWithoutUserScope.specific',
                        'workerWithoutUserScope',
                        'hallWithoutUserScope'
                    ])->first()->toArray();
            }
            
            if(!empty($moving_event['client']) && is_numeric($moving_event['client'])){
                $output_moving_event['client'] = Client::where('id', (int)$moving_event['client'])
                    ->first()->toArray();
            }
            
            if(!empty($moving_event['picked']) && is_array($moving_event['picked'])){
                $picked = $moving_event['picked'];
                $output_moving_event['picked'] = [];
                if(!empty($picked['hall']) && is_numeric($picked['hall'])){
                    $output_moving_event['picked']['hall'] = Hall::where('id', (int)$picked['hall'])
                        ->first()->toArray();
                }
                if(!empty($picked['worker']) && is_numeric($picked['worker'])){
                    $output_moving_event['picked']['worker'] = Worker::where('id', (int)$picked['worker'])
                        ->first()->toArray();
                }
                if(!empty($picked['template']) && is_numeric($picked['template'])){
                    $output_moving_event['picked']['template'] = Template::where('id', (int)$picked['template'])
                        ->first()->toArray();
                }
            }
            
            if(!empty($output_moving_event)){
                $output_moving_event['show'] = !empty($moving_event['show']) && $moving_event['show'] === true ? true : false;
                $output['moving_event'] = $output_moving_event;
            }
        }
        
        // dd($output);
        
        if(!empty($filtered_hall) || !empty($filtered_worker) || !empty($filtered_template)){
            $output['filters'] = [
                'hall' => !empty($filtered_hall) ? $filtered_hall : null,
                'worker' => !empty($filtered_worker) ? $filtered_worker : null,
                'template' => !empty($filtered_template) ? $filtered_template : null,
                'view' => !empty($filtered_view) ? $filtered_view : null,
            ];
            if(empty($output['filters']['hall'])){
                $output['filters'] = null;
            }else{
                if(empty($output['filters']['worker'])){
                    $output['filters']['worker'] = null;
                    $output['filters']['template'] = null;
                }else{
                    if(empty($output['filters']['template']))
                        $output['filters']['template'] = null;
                }
            }
        }
        
        // $output['filters'] = null;
        
        // dd($output);
        return view('dashboard.bookings.index', $output);
    }
}
