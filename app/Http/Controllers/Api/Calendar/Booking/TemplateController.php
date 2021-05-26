<?php

namespace App\Http\Controllers\Api\Calendar\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\User;
use App\Models\Hall;
use App\Models\Template;
use App\Scopes\UserScope;

class TemplateController extends Controller
{
    public function index(Request $request, User $user){
        
        $template_model = Template::withoutGlobalScope(UserScope::class)->with('specific')
            ->where('is_deleted', '0')->byUser($user->id);
        
        if($request->exists('hall')){
            // $hall = Hall::withoutGlobalScope(UserScope::class)->find($request->hall);
            $hall = Hall::withoutGlobalScope(UserScope::class)->byUser($user->id)->byId($request->hall)->first();
            $template_model->whereHas('workers', function($query) use ($request, $user) {
                $query->withoutGlobalScope(UserScope::class)->byUser($user->id)->whereHas('halls', function($query) use ($request, $user) {
                    // $query->withoutGlobalScope(UserScope::class)->where('halls.id', $request->hall);
                    $query->withoutGlobalScope(UserScope::class)->byUser($user->id)->byId($request->hall);
                });
            });
        }
        
        if($request->exists('worker')){
            // $worker = Worker::withoutGlobalScope(UserScope::class)->find($request->worker);
            $worker = Worker::withoutGlobalScope(UserScope::class)->byUser($user->id)->byId($request->worker)->first();
            if($request->exists('hall')){
                $template_model->orWhereHas('workers', function($query) use ($request, $user) {
                    // $query->withoutGlobalScope(UserScope::class)->where('workers.id', $request->worker);
                    $query->withoutGlobalScope(UserScope::class)->byUser($user->id)->byId($request->worker);
                });
            }else{
                $template_model->whereHas('workers', function($query) use ($request, $user) {
                    // $query->withoutGlobalScope(UserScope::class)->where('workers.id', $request->worker);
                    $query->withoutGlobalScope(UserScope::class)->byUser($user->id)->byId($request->worker);
                });
            }
        }
        
        $templates = $template_model->get();
        
        $output = [
            'templates' => $templates->toArray(),
            // 'user' => $user->id
        ];
        
        if(!empty($hall))
            $output['hall'] = $hall;
        
        if(!empty($worker))
            $output['worker'] = $worker;
        
        return response()->json($output);
    }
    
    // public function index(Request $request, User $user){
    // 
    //     $template_model = Template::withoutGlobalScope(UserScope::class)->where('is_deleted', '=', '0');
    // 
    //     if($request->exists('hall')){
    //         $hall = Hall::withoutGlobalScope(UserScope::class)->find($request->hall);
    //         $template_model->whereHas('workers', function($query) use ($request) {
    //             $query->withoutGlobalScope(UserScope::class)->whereHas('halls', function($query) use ($request) {
    //                 $query->withoutGlobalScope(UserScope::class)->where('halls.id', $request->hall);
    //             });
    //         });
    //     }
    // 
    //     if($request->exists('worker')){
    //         $worker = Worker::withoutGlobalScope(UserScope::class)->find($request->worker);
    //         if($request->exists('hall')){
    //             $template_model->orWhereHas('workers', function($query) use ($request) {
    //                 $query->withoutGlobalScope(UserScope::class)->where('workers.id', $request->worker);
    //             });
    //         }else{
    //             $template_model->whereHas('workers', function($query) use ($request) {
    //                 $query->withoutGlobalScope(UserScope::class)->where('workers.id', $request->worker);
    //             });
    //         }
    //     }
    // 
    //     $templates = $template_model->get();
    // 
    //     $output = [
    //         'templates' => $templates->toArray(),
    //         // 'user' => $user->id
    //     ];
    // 
    //     if(!empty($hall))
    //         $output['hall'] = $hall;
    // 
    //     if(!empty($worker))
    //         $output['worker'] = $worker;
    // 
    //     return response()->json($output);
    // }
}
