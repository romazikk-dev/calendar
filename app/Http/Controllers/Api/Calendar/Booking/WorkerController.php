<?php

namespace App\Http\Controllers\Api\Calendar\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Hall;
use App\Models\Template;
use App\Scopes\UserScope;

class WorkerController extends Controller
{
    public function index(Request $request){
        
        $model = Worker::withoutGlobalScope(UserScope::class)->where('is_deleted', '=', '0');
        
        if($request->exists('hall')){
            $hall = Hall::withoutGlobalScope(UserScope::class)->find($request->hall);
            $model->whereHas('halls', function($query) use ($request) {
                // $query->withoutGlobalScope(UserScope::class)->whereHas('halls', function($query) use ($request) {
                    $query->withoutGlobalScope(UserScope::class)->where('halls.id', $request->hall);
                // });
            });
        }
        
        if($request->exists('template')){
            $worker = Template::withoutGlobalScope(UserScope::class)->find($request->template);
            if($request->exists('hall')){
                $model->orWhereHas('templates', function($query) use ($request) {
                    $query->withoutGlobalScope(UserScope::class)->where('templates.id', $request->template);
                });
            }else{
                $model->whereHas('templates', function($query) use ($request) {
                    $query->withoutGlobalScope(UserScope::class)->where('templates.id', $request->template);
                });
            }
        }
        
        // if($request->exists('hall') && $hall = Hall::withoutGlobalScope(UserScope::class)->find($request->hall))
        //     // $hall = Hall::find();
        //     // $worker_model->where('is_deleted', '=', '0');
        //     $worker_model->whereHas('halls', function($query) use ($request) {
        //         $query->withoutGlobalScope(UserScope::class)->where('halls.id', $request->hall);
        //     });
        
        $workers = $model->get();
        
        $output = [
            'workers' => $workers->toArray()
        ];
        
        if(!empty($hall))
            $output['hall'] = $hall;
        
        return response()->json($output);
    }
}
