<?php

namespace App\Http\Controllers\Api\Calendar\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Worker;
use App\Models\Hall;
use App\Models\Template;
use App\Scopes\UserScope;

use Illuminate\Support\Facades\Validator;
use App\Classes\Getter\Enums\Keys as GetterKeys;
use App\Classes\Getter\Worker\Enums\Params as WorkerGetterParams;

class WorkerController extends Controller
{
    // public function index(Request $request, User $user){
    // 
    //     $model = Worker::withoutGlobalScope(UserScope::class)->where('is_deleted', '=', '0');
    // 
    //     if($request->exists('hall')){
    //         $hall = Hall::withoutGlobalScope(UserScope::class)->find($request->hall);
    //         $model->whereHas('halls', function($query) use ($request) {
    //             // $query->withoutGlobalScope(UserScope::class)->whereHas('halls', function($query) use ($request) {
    //                 $query->withoutGlobalScope(UserScope::class)->where('halls.id', $request->hall);
    //             // });
    //         });
    //     }
    // 
    //     if($request->exists('template')){
    //         $worker = Template::withoutGlobalScope(UserScope::class)->find($request->template);
    //         if($request->exists('hall')){
    //             $model->orWhereHas('templates', function($query) use ($request) {
    //                 $query->withoutGlobalScope(UserScope::class)->where('templates.id', $request->template);
    //             });
    //         }else{
    //             $model->whereHas('templates', function($query) use ($request) {
    //                 $query->withoutGlobalScope(UserScope::class)->where('templates.id', $request->template);
    //             });
    //         }
    //     }
    // 
    //     // if($request->exists('hall') && $hall = Hall::withoutGlobalScope(UserScope::class)->find($request->hall))
    //     //     // $hall = Hall::find();
    //     //     // $worker_model->where('is_deleted', '=', '0');
    //     //     $worker_model->whereHas('halls', function($query) use ($request) {
    //     //         $query->withoutGlobalScope(UserScope::class)->where('halls.id', $request->hall);
    //     //     });
    // 
    //     $workers = $model->get();
    // 
    //     $output = [
    //         'workers' => $workers->toArray()
    //     ];
    // 
    //     if(!empty($hall))
    //         $output['hall'] = $hall;
    // 
    //     return response()->json($output);
    // }
    
    public function index(Request $request, User $user){
        $validation_rules = [];
        foreach([
            ['key' => 'id', 'table' => 'workers'],
            ['key' => 'template_id', 'table' => 'templates'],
            ['key' => 'hall_id', 'table' => 'halls'],
        ] as $p){
            if($request->has($p['key'])){
                $request_item = $request->get($p['key']);
                if(is_array($request_item)){
                    $validation_rules[$p['key']] = 'nullable|array';
                    $validation_rules[$p['key'] . '.*'] = 'nullable|integer|exists:' . $p['table'] . ',id';
                }else{
                    $validation_rules[$p['key']] = 'nullable|numeric|exists:' . $p['table'] . ',id';
                }
            }
        }
        
        $validation_rules = array_merge($validation_rules, [
            'with' => 'nullable|array',
            'with.*' => 'nullable|string',
        ]);
        
        $validated = Validator::make($request->all(), $validation_rules)->validate();
            
        $params = [];
        foreach(WorkerGetterParams::all(['except' => [WorkerGetterParams::OWNER_ID]]) as $getter_param){
            if(!empty($validated[$getter_param]))
                $params[$getter_param] = $validated[$getter_param];
        }
        
        $params[WorkerGetterParams::OWNER_ID] = $user->id;
        // $params[WorkerGetterParams::OWNER_ID] = 1;
        
        $result = \Getter::of(GetterKeys::WORKERS)->get($params);
        return response()->json([
            'workers' => $result
        ]);
    }
}
