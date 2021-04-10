<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\PersonController as Controller;
use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\WorkerSuspension;
use App\Providers\RouteServiceProvider;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(111);
        return view('dashboard.worker.index');
    }
    
    /**
     * Return list of workers for ajax request.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataList()
    {        
        $workers = Worker::select([
            // 'workers.`id`',
            DB::raw("workers.`id` as `id`"),
            DB::raw("workers.`first_name`"),
            DB::raw("workers.`last_name`"),
            DB::raw("CONCAT(workers.`first_name`,' ',workers.`last_name`) as `full_name`"),
            'email',
            // 'created_at',
            DB::raw("workers.`created_at` as `created_at`"),
            DB::raw("workers_suspensions.`id` as worker_suspension_id"),
            DB::raw("workers_suspensions.`from` as worker_suspension_from"),
            DB::raw("workers_suspensions.`to` as worker_suspension_to"),
        ])
        ->leftJoin('workers_suspensions', 'workers.id', '=', 'workers_suspensions.worker_id')
        ->where('is_deleted', 0);
        
        return Datatables::eloquent($workers)->filterColumn('full_name', function($query, $keyword) {
                    $sql = "CONCAT(first_name,' ',last_name)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })->toJson(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.worker.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'max:255',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|regex:/^\+[0-9]{3,20}$/i',
            'birthdate' => 'nullable|regex:/\d{4}-\d{2}-\d{2}/i',
            'country' => 'max:255',
            'town' => 'max:255',
            'street' => 'max:255',
            'email' => 'required|email|unique:workers|max:255',
            'email' => [
                'required', 'email', ['max', 255], Rule::unique('workers')->where(function($query) {
                    // $query->where('is_deleted', '=', '0')->where('id', '!=', $id);
                    $query->where('is_deleted', '=', '0');
                })
            ],
            'password' => 'required|max:255',
            'password_confirm' => 'required|same:password',
        ]);
        
        $validated['user_id'] = auth()->user()->id;
        $validated['password'] = Hash::make($validated['password']);
        
        // $birthdate_arr = explode('-', $validated['birthdate']);
        // $validated['birthdate'] = $birthdate_arr[2] . '-' . $birthdate_arr[1] . '-' . $birthdate_arr[0];
        $validated['birthdate'] = $this->parseToCorrectDBDate($validated['birthdate']);
        
        Worker::create($validated);
        
        return redirect()->route('dashboard.worker.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd('show');
        $worker = Worker::find($id);
        // dd($worker);
        return view('dashboard.worker.show', [
            'worker' => $worker
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd(111);
        $worker = Worker::find($id);
        return view('dashboard.worker.create', [
            'worker' => $worker
        ]);
    }
    
    /**
     * Toggle worker suspension.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleSuspension(Request $request, $id)
    {        
        $validated = $request->validate([
            'type' => 'required|in:complete,period,disable',
            'from' => 'required_if:type,period|nullable|regex:/\d{2}-\d{2}-\d{4}/i',
            'to' => 'required_if:type,period|nullable|regex:/\d{2}-\d{2}-\d{4}/i'
        ]);
        
        $worker = Worker::find($id);
        
        if(empty($worker))
            return response()->json([
                'status' => 'fail',
                'worker_id' => $id,
                'type' => $validated['type'],
                'from' => !empty($validated['from']) ? $validated['from'] : null,
                'to' => !empty($validated['to']) ? $validated['to'] : null,
                'msg' => 'Worker with `id` - ' . $id . ' does not exist'
            ]);
        
        $workerSuspension = WorkerSuspension::where('worker_id', $worker->id)->first();
        
        if(!empty($workerSuspension)){
            if($validated['type'] == 'disable'){
                $workerSuspension->forceDelete();
                return response()->json([
                    'status' => 'success',
                    'worker_id' => $id,
                    'type' => $validated['type'],
                ]);
            }elseif($validated['type'] == 'complete'){
                $workerSuspension->from = null;
                $workerSuspension->to = null;
                $workerSuspension->save();
                return response()->json([
                    'status' => 'success',
                    'worker_id' => $workerSuspension->worker_id,
                    'suspension_id' => $workerSuspension->id,
                    // 'from' => $workerSuspension->from,
                    // 'to' => $workerSuspension->to,
                    'type' => $validated['type'],
                ]);
            }elseif($validated['type'] == 'period'){
                $workerSuspension->from = $this->formatDate($validated['from']);
                $workerSuspension->to = $this->formatDate($validated['to']);
                $workerSuspension->save();
                return response()->json([
                    'status' => 'success',
                    'worker_id' => $id,
                    'suspension_id' => $workerSuspension->id,
                    'type' => $validated['type'],
                    'from' => $workerSuspension->from,
                    'to' => $workerSuspension->to
                ]);
            }
        }else{
            $workerSuspension = new WorkerSuspension;
            if($validated['type'] == 'complete'){
                $workerSuspension->worker_id = $worker->id;
                $workerSuspension->from = null;
                $workerSuspension->to = null;
                $workerSuspension->save();
                return response()->json([
                    'status' => 'success',
                    'worker_id' => $id,
                    'suspension_id' => $workerSuspension->id,
                    'type' => $validated['type'],
                ]);
            }elseif($validated['type'] == 'period'){
                $workerSuspension->worker_id = $worker->id;
                $workerSuspension->from = $this->formatDate($validated['from']);
                $workerSuspension->to = $this->formatDate($validated['to']);
                $workerSuspension->save();
                return response()->json([
                    'status' => 'success',
                    'worker_id' => $id,
                    'suspension_id' => $workerSuspension->id,
                    'type' => $validated['type'],
                    'from' => $workerSuspension->from,
                    'to' => $workerSuspension->to
                ]);
            }
        }
        
        return response()->json([
            'status' => 'fail',
            'worker_id' => $id,
            'type' => $validated['type'],
            'from' => !empty($validated['from']) ? $validated['from'] : null,
            'to' => !empty($validated['to']) ? $validated['to'] : null,
        ]);
    }
    
    /**
     * Formats a date for mysql.
     *
     * @param string $date
     * @return string
     */
     protected function formatDate($date)
     {
         $arr = explode('-', $date);
         return $arr[2] . '-' . $arr[1] . '-' . $arr[0];
     }
    
    /**
     * Show the form for editing the password.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPassword($id)
    {
        // dd(111);
        $worker = Worker::find($id);
        return view('dashboard.worker.create_password', [
            'worker' => $worker
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd('update');
        $validated = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'max:255',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|regex:/^\+[0-9]{3,20}$/i',
            'birthdate' => 'nullable|regex:/\d{4}-\d{2}-\d{2}/i',
            'country' => 'max:255',
            'town' => 'max:255',
            'street' => 'max:255',
            'email' => 'required|email|unique:workers|max:255',
            'email' => [
                'required', 'email', ['max', 255], Rule::unique('workers')->where(function($query) use ($id) {
                    $query->where('is_deleted', '=', '0')->where('id', '!=', $id);
                })
            ],
            // 'email' => 'required|email|unique:workers|max:255',
            // 'password' => 'max:255',
            // 'password_confirm' => 'same:password',
            // 'title' => 'required|unique:posts|max:255',
            // 'body' => 'required',
        ]);
        
        // dd($validated);
        
        // $worker = Worker::create($validated);
        // if(!empty($validated['password']))
        //     $validated['password'] = Hash::make($validated['password']);
        // 
        // if(empty($validated['password']))
        //     unset($validated['password']);
        // unset($validated['password_confirm']);
        
        // foreach($validated as $k => $v){
        //     if(empty($v) && in_array($v, [
        //         ''
        //     ]))
        //         unset($validated[$k]);
        // }
        
        DB::table('workers')
            ->where('id', $id)
            ->update($validated);
        
        return redirect()->back()->with('success', 'Data saccessfuly saved!');
    }
    
    /**
     * Update the specified workers password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $id)
    {
        // dd('update');
        $validated = $request->validate([
            'password' => 'required|max:255',
            'password_confirm' => 'required|same:password',
        ]);
        
        // dd($validated);
        
        // $worker = Worker::create($validated);
        // if(!empty($validated['password']))
        //     $validated['password'] = Hash::make($validated['password']);
        // 
        // if(empty($validated['password']))
        //     unset($validated['password']);
        
        $validated['password'] = Hash::make($validated['password']);
        unset($validated['password_confirm']);
        
        // foreach($validated as $k => $v){
        //     if(empty($v) && in_array($v, [
        //         ''
        //     ]))
        //         unset($validated[$k]);
        // }
        
        DB::table('workers')
            ->where('id', $id)
            ->update($validated);
        
        return redirect()->back()->with('status', 'Success!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Worker::where('id', $id)
            ->where('is_deleted', 0)
            ->update(['is_deleted' => 1]);
            
        return redirect()->route('dashboard.worker.index');
        // dd($id);
    }
}
