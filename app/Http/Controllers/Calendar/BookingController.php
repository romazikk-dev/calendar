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
        // dd($owner_id);
        if($owner = User::find($owner_id)){
            // dd(UserScope::class);
            // $halls = Hall::all();
            $halls = Hall::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id)->get();
            $workers = Worker::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id)->get();
            $templates = Template::withoutGlobalScope(UserScope::class)->where('user_id', '=', $owner->id)->get();
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
            return view('calendar.booking.index', [
                'owner' => $owner,
                'halls' => $halls->toArray(),
                'workers' => $workers->toArray(),
                'templates' => $templates->toArray(),
            ]);
        }else{
            abort(404);
        }
    }
    
}
