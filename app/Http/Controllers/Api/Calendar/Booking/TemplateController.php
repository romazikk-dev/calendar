<?php

namespace App\Http\Controllers\Api\Calendar\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Hall;
use App\Models\Template;
use App\Scopes\UserScope;

class TemplateController extends Controller
{
    public function index(Request $request){
        
        $template_model = Template::withoutGlobalScope(UserScope::class)->where('is_deleted', '=', '0');
        
        if($request->exists('hall')){
            $hall = Hall::withoutGlobalScope(UserScope::class)->find($request->hall);
            $template_model->whereHas('workers', function($query) use ($request) {
                $query->withoutGlobalScope(UserScope::class)->whereHas('halls', function($query) use ($request) {
                    $query->withoutGlobalScope(UserScope::class)->where('halls.id', $request->hall);
                });
            });
        }
        
        if($request->exists('worker')){
            $worker = Worker::withoutGlobalScope(UserScope::class)->find($request->worker);
            if($request->exists('hall')){
                $template_model->orWhereHas('workers', function($query) use ($request) {
                    $query->withoutGlobalScope(UserScope::class)->where('workers.id', $request->worker);
                });
            }else{
                $template_model->whereHas('workers', function($query) use ($request) {
                    $query->withoutGlobalScope(UserScope::class)->where('workers.id', $request->worker);
                });
            }
        }
        
        $templates = $template_model->get();
        
        $output = [
            'templates' => $templates->toArray()
        ];
        
        if(!empty($hall))
            $output['hall'] = $hall;
        
        if(!empty($worker))
            $output['worker'] = $worker;
        
        return response()->json($output);
    }
}
