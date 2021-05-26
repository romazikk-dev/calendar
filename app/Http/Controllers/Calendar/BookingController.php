<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hall;
use App\Models\Worker;
use App\Models\Template;
use App\Scopes\UserScope;

class BookingController extends Controller{
    
    function index(Request $request, $owner_id){
        // dd(33);
        $filters = !empty($_COOKIE['filters']) ? json_decode($_COOKIE['filters']) : null;
        
        // dd($_COOKIE['filters']);
            
        if($owner = User::find($owner_id)){
            // dd(UserScope::class);
            // $halls = Hall::all();
            
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
            $template_model = Template::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id);
            
            $halls = $hall_model->get();
            $workers = $worker_model->get();
            $templates = $template_model->get();
            
            if(!empty($filters) && isset($filters->hall))
                $filtered_hall = $hall_model->where('id', '=', (int)$filters->hall)->first();
            if(!empty($filters) && isset($filters->worker))
                $filtered_worker = $worker_model->where('id', '=', (int)$filters->worker)->first();
            if(!empty($filters) && isset($filters->template))
                $filtered_template = $template_model->where('id', '=', (int)$filters->template)->first();
            if(!empty($filters) && isset($filters->view))
                $filtered_view = $filters->view;
            
            // dd($halls, $workers, $templates);
            // $halls = Hall::withoutGlobalScope(ucfirst(UserScope::class))->get();
            // $halls = Hall::withoutGlobalScope('App\Scopes\UserScope')->get();
            // $halls = Hall::withoutGlobalScopes([UserScope::class])->get();
            // $workers = Worker::all();
            // dd(1111);
            // $composed_halls = [];
            // foreach($halls as $hall){
            //     $itm = $hall->toArray();
            //     // $itm['workers'] = $hall->workers->toArray();
            //     $workers = $hall->workersWithoutGlobalScope;
            //     // dd($workers);
            //     if(!empty($workers)){
            //         $composed_workers = [];
            //         foreach($workers as $worker){
            // 
            //         }
            //     }
            //     $itm['workers'] = $hall->workersWithoutGlobalScope->toArray();
            //     $composed_halls[$hall->id] = $itm;
            // 
            //     // dump($hall->workers->toArray());
            //     // $arr = [];
            //     // foreach($hall->workers as $worker){
            //     //     $arr[] = $worker->toArray();
            //     // }
            //     // 
            //     // $workers_per_hall[$hall->id] = $arr;
            //     // $workers_per_hall[$hall->id] = $hall->workers->toArray();
            // }
            // dd();
            // dd($workers_per_hall);
            $output = [
                'owner' => $owner,
                'halls' => $halls->toArray(),
                'workers' => $workers->toArray(),
                'templates' => $templates->toArray(),
                'filters' => null,
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
