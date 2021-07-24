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
        
        $duration_range = \DurationRange::get();
        $admins_booking_calendar_main_settings = \Setting::of(SettingKeys::ADMINS_BOOKING_CALENDAR_MAIN)->getOrPlaceholder();
            
        $dashboard_calendar_move_event = !empty($_COOKIE['dashboard_calendar_move_event']) ?
            json_decode($_COOKIE['dashboard_calendar_move_event']) : null;
        $token = !empty($_COOKIE['token']) ? $_COOKIE['token'] : null;
        
        
        $moving_event = !empty($_COOKIE['moving_event']) ? json_decode($_COOKIE['moving_event'], true) : null;
        $new_event = !empty($_COOKIE['dashboard_calendar-new_event']) ? json_decode($_COOKIE['dashboard_calendar-new_event'], true) : null;
        
        // dd(\Calendar::dashboard()->view()->getView());
        // $view = \Calendar::dashboard()->view()->getView();
        
        // if(!empty($view))
        //     // dd($_COOKIE['dashboard_calendar-view']);
        //     dd($view);
        
        $hall_model = Hall::query();
        
        
        $worker_model = Worker::query();
        $template_model = Template::query()->with('specific');
        
        $halls = $hall_model->get();
        
        
        
        $db_specifics = TemplateSpecifics::all()->toArray();
        if(!empty($db_specifics))
            $parsed_specifics = \Specifics::parseDbReesultToTreeArray($db_specifics, true);
        
        $db_specifics_arr_as_key_index = [];
        foreach($db_specifics as $k => $v)
            $db_specifics_arr_as_key_index[$v['id']] = $v;
        
        $output = [
            // 'token' => $token,
            // 'owner' => auth()->user()->toArray(),
            'owner' => auth()->user(),
            // 'halls' => $halls->toArray(),
            'halls' => \Getter::of(GetterKeys::HALLS)->get()->toArray(),
            'template_specifics' => !empty($parsed_specifics) ? $parsed_specifics : [],
            'template_specifics_as_id_key' => !empty($db_specifics_arr_as_key_index) ? $db_specifics_arr_as_key_index : [],
            // 'workers' => $workers->toArray(),
            // 'templates' => $templates->toArray(),
            'filters' => \Filter::getFromCookie(),
            // 'filters' => null,
            'view' => \Calendar::dashboard()->view()->getView(),
            'moving_event' => null,
            'new_event' => null,
            'custom_titles' => \Setting::of(SettingKeys::CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES)->getOrPlaceholder(),
            'calendar_settings' => $admins_booking_calendar_main_settings,
            'duration_range' => $duration_range,
            // 'template_main_settings' => $template_main_settings,
        ];
        
        // Set up variable `$new_event` from cookie
        if(!empty($new_event)){
            $output_new_event = [];
            if(!empty($new_event['client']) && is_numeric($new_event['client'])){
                $output_new_event['client'] = Client::where('id', (int)$new_event['client'])
                    ->first()->toArray();
            }
        
            if(!empty($new_event['main']) && is_array($new_event['main'])){
                $main = $new_event['main'];
                $output_new_event['main'] = [];
                if(!empty($main['hall']) && is_numeric($main['hall'])){
                    $output_new_event['main']['hall'] = Hall::where('id', (int)$main['hall'])
                        ->first()->toArray();
                }
                if(!empty($main['worker']) && is_numeric($main['worker'])){
                    $output_new_event['main']['worker'] = Worker::where('id', (int)$main['worker'])
                        ->first()->toArray();
                }
                if(!empty($main['template']) && is_numeric($main['template'])){
                    $output_new_event['main']['template'] = Template::where('id', (int)$main['template'])
                        ->with([
                            'specificWithoutUserScope',
                        ])->first()->toArray();
                }
            }
        
            if(!empty($output_new_event)){
                $output_new_event['show'] = !empty($new_event['show']) && $new_event['show'] === true ? true : false;
                $output['new_event'] = $output_new_event;
            }
        }
        
        // dd($output_new_event);
        // dd($output);
        // dd($new_event);
        
        // Set up variable `$moving_event` from cookie
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
                        ->with([
                            'specificWithoutUserScope',
                        ])->first()->toArray();
                }
            }
            
            if(!empty($output_moving_event)){
                $output_moving_event['show'] = !empty($moving_event['show']) && $moving_event['show'] === true ? true : false;
                $output['moving_event'] = $output_moving_event;
            }
        }
        
        // $output['filters'] = null;
        
        // dd($output);
        return view('dashboard.bookings.index', $output);
    }
}
