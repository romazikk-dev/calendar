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
use App\Classes\Range\Range;

class BookController extends Controller{
    
    // public function test(Request $request, User $user){
    //     // echo $user_id;
    //     echo 111;
    //     die();
    // }
    
    public function all(Request $request, User $user, $from_date = null, $to_date = null){
        $client = $request->user();
        
        $booking_model = Booking::withoutGlobalScope(UserScope::class)->with(
            'templateWithoutUserScope',
            'workerWithoutUserScope',
            'hallWithoutUserScope'
        )->byUser($user->id)->byClient($client->id)->orderBy('time', 'ASC');
        
        // var_dump($booking_model->get()->toArray());
        // var_dump($user->id);
        // var_dump($client->id);
        // die();
        
        // if(!is_null($from_date)){
        //     $from_date_arr = explode('_', $from_date);
        //     $booking_model->where('time', '>=', $from_date_arr[0] . ' ' . $from_date_arr[1]);
        // }
        // $from_date_arr = explode('_', $from_date);
        // $from_date_carbon = \Carbon\Carbon::parse('');
        // $only_date_arr = explode('-', $from_date_arr[0]);
        
        // if(!is_null($from_date))
        //     $booking_model->where('time', '>=', $from_date_arr[0] . ' ' . $from_date_arr[1]);
        
        $bookings = $booking_model->get();
        return response()->json($bookings->toArray());
    }
    
    public function create(Request $request, User $user, $hall_id, $template_id, $worker_id){
    // public function create(Request $request, $user_id, $hall_id, $template_id, $worker_id){
        
        // book_on_date: this.bookingDate,
        // book_on_time: this.bookOn,
        
        $validated = $request->validate([
            'book_on_date' => 'required|string|max:10|regex:/\d{4}-\d{2}-\d{2}/i',
            'book_on_time' => 'required|string|max:10|regex:/\d{2}:\d{2}/i',
        ]);
        
        $book_on_datetime = $validated['book_on_date'] . " " . $validated['book_on_time'] . ":00";
        
        // $book_on_carbon = \Carbon\Carbon::parse($book_on_datetime);
        // $current_date_carbon = \Carbon\Carbon::now('Europe/Kiev');
        // 
        // var_dump($book_on_carbon->format('Y-m-d H:i:s'));
        // var_dump($current_date_carbon->format('Y-m-d H:i:s'));
        // die();
        
        $client = $request->user();
        
        //Check hall
        if(!($hall = Hall::withoutGlobalScope(UserScope::class)->byUser($user->id)->byId($hall_id)->first()))
            return response()->json([
                'error' => 'Hall not exist with :id = '.$hall_id
            ]);
        
        if(\Suspension::isSuspendedOnDate($hall, $book_on_datetime))
            return response()->json([
                'error' => 'Hall is suspended on ' . $book_on_datetime
            ]);
        
        //Check worker
        if(!($worker = Worker::withoutGlobalScope(UserScope::class)->byUser($user->id)->byId($worker_id)->first()))
            return response()->json([
                'error' => 'Worker not exist with :id = ' . $worker_id
            ]);
        
        if(\Suspension::isSuspendedOnDate($worker, $book_on_datetime))
            return response()->json([
                'error' => 'Worker is suspended on ' . $book_on_datetime
            ]);
        
        $all_holidays = \Holiday::getAllAsUniqueDateValue($hall, $worker, [
            'user_id' => $user->id
        ]);
        $for_holiday_check_val = str_replace('-', '_', $validated['book_on_date']);
        if(in_array($for_holiday_check_val, $all_holidays))
            return response()->json([
                'error' => 'You trying book on date wich is holiday right now.'
            ]);
            
        // var_dump($validated['book_on_date']);
        // var_dump($for_holiday_check_val);
        // var_dump($all_holidays);
        // var_dump($book_on_datetime);
        // var_dump('success');
        // die();
        
        $template = Template::withoutGlobalScope(UserScope::class)
            ->byUser($user->id)
            ->byId($template_id)
            ->whereHas('workers', function($query) use ($user, $hall_id, $worker_id, &$errors) {
                // $query = $query->withoutGlobalScope(UserScope::class)->where('workers.id', $worker_id);
                // if(empty($query->first()))
                //     $errors['worker'] = 'Worker not exist with :id = '.$worker_id;
                // $query->whereHas('halls', function($query) use ($hall_id) {
                //     $query->withoutGlobalScope(UserScope::class)->where('halls.id', $hall_id);
                // });
                $query->withoutGlobalScope(UserScope::class)
                    ->byUser($user->id)
                    ->byId($worker_id)
                    ->whereHas('halls', function($query) use ($user, $hall_id) {
                        $query->withoutGlobalScope(UserScope::class)
                            ->byUser($user->id)
                            ->byId($hall_id);
                    });
            })->first();
        
        if(empty($template))
            // $errors['params'] = 'Bad data for :hall_id, :template_id, :worker_id';
            return response()->json([
                'error' => 'Bad data for :hall_id, :template_id, :worker_id'
            ]);
        
        $booking = Booking::create([
            'user_id' => $user->id,
            'hall_id' => $hall_id,
            'template_id' => $template_id,
            'worker_id' => $worker_id,
            'client_id' => $client->id,
            'time' => $validated['book_on_date'] . ' ' . $validated['book_on_time'] . ':00',
        ]);
        
        return response()->json([
            'status' => 'success'
        ]);
    }
    
    // public function cancel(Request $request, User $user, $hall_id, $template_id, $worker_id, $booking_id){
    public function cancel(Request $request, User $user, $booking_id){
        
        $client = $request->user();
        
        // var_dump($client->toArray());
        // die();
        // if(!($user = User::find($user_id)))
        //     abort(404, 'Page not found');
            
        $booking = Booking::withoutGlobalScope(UserScope::class)->byUser($user->id)->where([
            'id' => $booking_id,
            // 'user_id' => $user->id,
            // 'hall_id' => $hall_id,
            // 'template_id' => $template_id,
            // 'worker_id' => $worker_id,
            'client_id' => $client->id,
        ])->first();
        
        if(empty($booking))
            abort(400, 'Bad request');
            
        // if(empty($booking))
        //     return response()->json([
        //         'id' => $booking_id,
        //         'user_id' => $user->id,
        //         'hall_id' => $hall_id,
        //         'template_id' => $template_id,
        //         'worker_id' => $worker_id,
        //         'client_id' => $client->id,
        //     ]);
            
        $booking->delete();
        
        return response()->json([
            'status' => 'success',
            'msg' => 'Booking successfully deleted'
        ]);
    }
    
}
