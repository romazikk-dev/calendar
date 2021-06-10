<?php

namespace App\Http\Controllers\Dashboard;

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

class BookingsController extends Controller
{
    public function index(){
        
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
        $token = !empty($_COOKIE['token']) ? $_COOKIE['token'] : null;
        
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
        
        $owner_id = auth()->user()->id;
        
        $owner = User::find($owner_id);
        
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
        return view('dashboard.bookings.index', $output);
    }
    
    public function range(Request $request, $start, $end){
        $validated = $request->validate([
            'hall' => 'nullable|integer|exists:halls,id',
            'worker' => 'nullable|integer|exists:workers,id',
            'template' => 'nullable|integer|exists:templates,id',
            'client' => 'nullable|integer|exists:clients,id',
        ]);
        
        if(!empty($validated['hall']))
            $hall = Hall::find($validated['hall']);
        
        if(!empty($validated['worker']))
            $worker = Worker::find($validated['worker']);
            
        if(!empty($validated['template']))
            $template = Template::find($validated['template']);
        
        if(!empty($validated['client']))
            $client = Client::find($validated['client']);
        
        $range = new Range($start, $end, 'month');
        
        $retrieval = new BookedAndRequestedRetrieval($range, [
            "hall" => !empty($hall) ? $hall : null,
            "worker" => !empty($worker) ? $worker : null,
            "template" => !empty($template) ? $template : null,
            "client" => !empty($client) ? $client : null,
            // "past_ignore" => true,
        ]);
        
        return response()->json([
            'data' => $retrieval->get(),
        ]);
    }
    
    public function ajaxTemplatesByHall(Request $request, $hall_id){
        
    }
}
