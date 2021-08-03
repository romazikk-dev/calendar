<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hall;
use App\Models\Worker;
use App\Models\Template;
use App\Scopes\UserScope;
use App\Models\TemplateSpecifics;
use App\Classes\Setting\Enums\Keys as SettingKeys;

class BookingController extends Controller{
    
    function index(Request $request, $owner_id){
        // dd(33);
        $filters = !empty($_COOKIE['filters']) ? json_decode($_COOKIE['filters']) : null;
        $token = !empty($_COOKIE['token']) ? $_COOKIE['token'] : null;
        
        // dd($token);
        
        // dd($_COOKIE['filters']);
            
        if($owner = User::find($owner_id)){
            // dd(UserScope::class);
            // $halls = Hall::all();
            
            // $custom_titles = \Setting::of(SettingKeys::CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES)->getOrPlaceholder();
            // dd($custom_titles);
            
            $hall_model = Hall::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id);
            
            // $hall_model = Hall::withoutGlobalScope(UserScope::class)->whereHas('workers', function($query) use ($owner){
            //     // dd(3333);
            //     return $query->withoutGlobalScope(UserScope::class)->whereHas('templates', function($query) use ($owner){
            //         // return $query->where('user_id', $user->id)
            //         //     ->where('has_paid', true);
            //     })->byUser($owner->id);
            // })->byUser($owner->id)->first();
            
            // dd($hall_model->workers[0]->templates);
            // dd($hall_model->toArray());
            
            $worker_model = Worker::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id);
            $template_model = Template::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id)->with('specific');
            
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
            
            $output = [
                'token' => $token,
                'owner' => $owner,
                'halls' => $halls->toArray(),
                'template_specifics' => !empty($parsed_specifics) ? $parsed_specifics : [],
                'template_specifics_as_id_key' => !empty($db_specifics_arr_as_key_index) ? $db_specifics_arr_as_key_index : [],
                // 'workers' => $workers->toArray(),
                // 'templates' => $templates->toArray(),
                'filters' => null,
                'custom_titles' => \Setting::of(SettingKeys::CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES)->getOrPlaceholder(),
            ];
            
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
            return view('calendar.booking.index', $output);
        }else{
            abort(404);
        }
    }
    
    function indexAlias(Request $request, $alias){
        
        $owner_id = \CalendarAlias::getByAlias($alias, 'user_id');
        // dd($res);
        
        // $link = route('calendar.booking.alias', ['ddddd']);
        // dd($link);
        // dd($alias);
        $filters = !empty($_COOKIE['filters']) ? json_decode($_COOKIE['filters']) : null;
        $token = !empty($_COOKIE['token']) ? $_COOKIE['token'] : null;
        
        // dd($token);
        
        // dd($_COOKIE['filters']);
            
        if($owner = User::find($owner_id)){
            // dd(UserScope::class);
            // $halls = Hall::all();
            
            // $custom_titles = \Setting::of(SettingKeys::CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES)->getOrPlaceholder();
            // dd($custom_titles);
            
            $hall_model = Hall::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id);
            
            // $hall_model = Hall::withoutGlobalScope(UserScope::class)->whereHas('workers', function($query) use ($owner){
            //     // dd(3333);
            //     return $query->withoutGlobalScope(UserScope::class)->whereHas('templates', function($query) use ($owner){
            //         // return $query->where('user_id', $user->id)
            //         //     ->where('has_paid', true);
            //     })->byUser($owner->id);
            // })->byUser($owner->id)->first();
            
            // dd($hall_model->workers[0]->templates);
            // dd($hall_model->toArray());
            
            $worker_model = Worker::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id);
            $template_model = Template::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id)->with('specific');
            
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
            
            $output = [
                'token' => $token,
                'owner' => $owner,
                'halls' => $halls->toArray(),
                'template_specifics' => !empty($parsed_specifics) ? $parsed_specifics : [],
                'template_specifics_as_id_key' => !empty($db_specifics_arr_as_key_index) ? $db_specifics_arr_as_key_index : [],
                // 'workers' => $workers->toArray(),
                // 'templates' => $templates->toArray(),
                'filters' => null,
                'custom_titles' => \Setting::of(SettingKeys::CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES)->getOrPlaceholder(),
            ];
            
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
            return view('calendar.booking.index', $output);
        }else{
            abort(404);
        }
    }
    
}
