<?php

namespace App\Http\Controllers\Api\Calendar\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Hall;
use App\Models\Template;
use App\Models\Worker;
use App\Exceptions\Api\Calendar\BadRangeException;
use App\Scopes\UserScope;

class BookController extends Controller{
    
    public function create(Request $request, $user_id, $hall_id, $template_id, $worker_id){
        
        // book_on_date: this.bookingDate,
        // book_on_time: this.bookOn,
        
        $validated = $request->validate([
            'book_on_date' => 'required|string|max:10|regex:/\d{4}-\d{2}-\d{2}/i',
            'book_on_time' => 'required|string|max:10|regex:/\d{2}:\d{2}/i',
        ]);
        
        // var_dump($validated);
        // die();
        
        if(!($user = User::find($user_id)))
            abort(404, 'Page not found');
        
        // $errors = [];
        if(!($hall = Hall::withoutGlobalScope(UserScope::class)->find($hall_id)))
            // $errors['hall'] = 'Hall not exist with :id = '.$hall_id;
            return response()->json([
                'error' => 'Hall not exist with :id = '.$hall_id
            ]);
        
        $template = Template::withoutGlobalScope(UserScope::class)->whereHas('workers', function($query) use ($hall_id, $worker_id, &$errors) {
            // $query = $query->withoutGlobalScope(UserScope::class)->where('workers.id', $worker_id);
            // if(empty($query->first()))
            //     $errors['worker'] = 'Worker not exist with :id = '.$worker_id;
            // $query->whereHas('halls', function($query) use ($hall_id) {
            //     $query->withoutGlobalScope(UserScope::class)->where('halls.id', $hall_id);
            // });
            $query->withoutGlobalScope(UserScope::class)->where('workers.id', $worker_id)->whereHas('halls', function($query) use ($hall_id) {
                $query->withoutGlobalScope(UserScope::class)->where('halls.id', $hall_id);
            });
        })->where('id', $template_id)->first();
        
        if(empty($template))
            // $errors['params'] = 'Bad data for :hall_id, :template_id, :worker_id';
            return response()->json([
                'error' => 'Bad data for :hall_id, :template_id, :worker_id'
            ]);
        
        $booking = Booking::create([
            'user_id' => $user_id,
            'hall_id' => $hall_id,
            'template_id' => $template_id,
            'worker_id' => $worker_id,
            'client_id' => 1,
            'time' => $validated['book_on_date'] . ' ' . $validated['book_on_time'] . ':00',
        ]);
        
        // dd($template);
            // $errors['template'] = 'Template not exist with :id = '.$template_id;
        // if(!($template = Template::withoutGlobalScope(UserScope::class)->find($template_id)))
        // ->whereHas('workers', function($query) use ($request) {
        //     $query->withoutGlobalScope(UserScope::class)->whereHas('halls', function($query) use ($request) {
        //         $query->withoutGlobalScope(UserScope::class)->where('halls.id', $request->hall);
        //     });
        // });
        
        //     $errors['template'] = 'Template not exist with :id = '.$template_id;
        
        // if(!($worker = Worker::withoutGlobalScope(UserScope::class)->find($worker_id)))
        //     $errors['worker'] = 'Worker not exist with :id = '.$worker_id;
            
        // if(!empty($errors)){
        //     return response()->json([
        //         'errors' => $errors
        //     ]);
        // }
    }
    
    public function cancel(Request $request, $user_id, $hall_id, $template_id, $worker_id, $booking_id){
        
        if(!($user = User::find($user_id)))
            abort(404, 'Page not found');
            
        $booking = Booking::where([
            'id' => $booking_id,
            'user_id' => $user_id,
            'hall_id' => $hall_id,
            'template_id' => $template_id,
            'worker_id' => $worker_id,
            'client_id' => 1,
        ])->first();
        
        if(empty($booking))
            abort(400, 'Bad request');
            
        $booking->delete();
        
        return response()->json([
            'status' => 'success',
            'msg' => 'Booking successfully deleted'
        ]);
        // var_dump($booking);
        // die();
    }
    
}
