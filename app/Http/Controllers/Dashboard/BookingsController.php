<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hall;
use App\Models\Worker;
use App\Models\Template;
use App\Scopes\UserScope;

class BookingsController extends Controller
{
    public function index(){
        
        $owner_id = 1;
        $filters = !empty($_COOKIE['filters']) ? json_decode($_COOKIE['filters']) : null;
            
        if($owner = User::find($owner_id)){
            
            $hall_model = Hall::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id);
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
            }
            // return view('calendar.booking.index', $output);
            return view('dashboard.bookings.index', $output);
        }else{
            abort(404);
        }
        
        
        
        // dd(111);
        // $owner = User::find(1);
        // 
        // $hall_model = Hall::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id);
        // $worker_model = Worker::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id);
        // $template_model = Template::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id);
        // 
        // $halls = $hall_model->get();
        // $workers = $worker_model->get();
        // $templates = $template_model->get();
        // 
        // return view('dashboard.bookings.index', [
        //     'owner' => $owner,
        //     'halls' => $halls->toArray(),
        //     'workers' => $workers->toArray(),
        //     'templates' => $templates->toArray(),
        //     'filters' => null,
        // ]);
    }
}
