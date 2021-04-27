<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hall;
use App\Models\Worker;
// use App\Models\HallWorkerAssignment;
use DB;
use Yajra\DataTables\Facades\DataTables;
use App\Classes\Suspension\ToogleSuspension;
use App\Classes\Suspension\Range;
use App\Classes\Setting\Enums\Keys as SettingKeys;

class HallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.hall.index');
    }
    
    /**
     * Return list of workers for ajax request.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataList()
    {        
        $workers = Hall::select([
            DB::raw("halls.`id`"),
            DB::raw("halls.`title`"),
            DB::raw("halls.`is_closed`"),
            DB::raw("halls.`created_at`"),
            DB::raw("(SELECT COUNT(*) FROM hall_worker WHERE hall_worker.`hall_id` = halls.`id`) as `workers_count`")
        ])
        ->with('suspension')->where('is_deleted', 0);
        
        return Datatables::eloquent($workers)->toJson(true);
        // return Datatables::eloquent($workers)->filterColumn('full_name', function($query, $keyword) {
        //             $sql = "CONCAT(first_name,' ',last_name)  like ?";
        //             $query->whereRaw($sql, ["%{$keyword}%"]);
        //         })->toJson(true);
    }
    
    /**
     * Toggle hall suspension.
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
    
        $hall = Hall::find($id);
        
        // return response()->json($hall->suspension);
    
        if(empty($hall))
            return response()->json([
                'status' => 'fail',
                'msg' => 'Hall with `id` - ' . $id . ' does not exist'
            ]);
        
        $toogle_suspension = new ToogleSuspension(
            $validated['type'],
            $hall,
            $validated['from'] ?? null,
            $validated['to'] ?? null
        );
        $toogle_suspension->toogle();
        
        return response()->json([
            'status' => 'success',
            'hall' => $hall,
            'type' => $validated['type'],
            'from' => $toogle_suspension->getFromDate(),
            'to' => $toogle_suspension->getToDate(),
        ]);
        
        // $workerSuspension = WorkerSuspension::where('worker_id', $worker->id)->first();
        // 
        // if(!empty($workerSuspension)){
        //     if($validated['type'] == 'disable'){
        //         $workerSuspension->forceDelete();
        //         return response()->json([
        //             'status' => 'success',
        //             'worker_id' => $id,
        //             'type' => $validated['type'],
        //         ]);
        //     }elseif($validated['type'] == 'complete'){
        //         $workerSuspension->from = null;
        //         $workerSuspension->to = null;
        //         $workerSuspension->save();
        //         return response()->json([
        //             'status' => 'success',
        //             'worker_id' => $workerSuspension->worker_id,
        //             'suspension_id' => $workerSuspension->id,
        //             // 'from' => $workerSuspension->from,
        //             // 'to' => $workerSuspension->to,
        //             'type' => $validated['type'],
        //         ]);
        //     }elseif($validated['type'] == 'period'){
        //         $workerSuspension->from = $this->formatDate($validated['from']);
        //         $workerSuspension->to = $this->formatDate($validated['to']);
        //         $workerSuspension->save();
        //         return response()->json([
        //             'status' => 'success',
        //             'worker_id' => $id,
        //             'suspension_id' => $workerSuspension->id,
        //             'type' => $validated['type'],
        //             'from' => $workerSuspension->from,
        //             'to' => $workerSuspension->to
        //         ]);
        //     }
        // }else{
        //     $workerSuspension = new WorkerSuspension;
        //     if($validated['type'] == 'complete'){
        //         $workerSuspension->worker_id = $worker->id;
        //         $workerSuspension->from = null;
        //         $workerSuspension->to = null;
        //         $workerSuspension->save();
        //         return response()->json([
        //             'status' => 'success',
        //             'worker_id' => $id,
        //             'suspension_id' => $workerSuspension->id,
        //             'type' => $validated['type'],
        //         ]);
        //     }elseif($validated['type'] == 'period'){
        //         $workerSuspension->worker_id = $worker->id;
        //         $workerSuspension->from = $this->formatDate($validated['from']);
        //         $workerSuspension->to = $this->formatDate($validated['to']);
        //         $workerSuspension->save();
        //         return response()->json([
        //             'status' => 'success',
        //             'worker_id' => $id,
        //             'suspension_id' => $workerSuspension->id,
        //             'type' => $validated['type'],
        //             'from' => $workerSuspension->from,
        //             'to' => $workerSuspension->to
        //         ]);
        //     }
        // }
        // 
        // return response()->json([
        //     'status' => 'fail',
        //     'worker_id' => $id,
        //     'type' => $validated['type'],
        //     'from' => !empty($validated['from']) ? $validated['from'] : null,
        //     'to' => !empty($validated['to']) ? $validated['to'] : null,
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // if({{ old('business_hours') ? old('business_hours')['monday']['start_hour'] : '' }})
        // if(!empty($request->old('business_hours')['monday'])){
        //     dd($request->old('business_hours')['monday']);
        // }
        $business_hours = \Setting::getOrPlaceholder(SettingKeys::DEFAULT_BUSSINESS_HOURS);
        return view('dashboard.hall.create', [
            'business_hours' => $business_hours,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $business_hour_rule = 'nullable|string|max:10|regex:/\d{2}:\d{2}/i';
        $business_weekend_rule = 'in:on';
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'max:1000',
            'short_description' => 'max:255',
            'notice' => 'max:1000',
            'country' => 'max:255',
            'town' => 'max:255',
            'street' => 'max:255',
            'is_closed' => 'required|in:0,1',
            'assign_worker' => 'nullable|array',
            'business_hours' => 'required|array',
            'business_hours.monday.start_hour' => $business_hour_rule,
            'business_hours.monday.end_hour' => $business_hour_rule,
            'business_hours.tuesday.start_hour' => $business_hour_rule,
            'business_hours.tuesday.end_hour' => $business_hour_rule,
            'business_hours.wednesday.start_hour' => $business_hour_rule,
            'business_hours.wednesday.end_hour' => $business_hour_rule,
            'business_hours.thursday.start_hour' => $business_hour_rule,
            'business_hours.thursday.end_hour' => $business_hour_rule,
            'business_hours.friday.start_hour' => $business_hour_rule,
            'business_hours.friday.end_hour' => $business_hour_rule,
            'business_hours.saturday.start_hour' => $business_hour_rule,
            'business_hours.saturday.end_hour' => $business_hour_rule,
            'business_hours.sunday.start_hour' => $business_hour_rule,
            'business_hours.sunday.end_hour' => $business_hour_rule,
            'business_hours.monday.is_weekend' => $business_weekend_rule,
            'business_hours.tuesday.is_weekend' => $business_weekend_rule,
            'business_hours.wednesday.is_weekend' => $business_weekend_rule,
            'business_hours.thursday.is_weekend' => $business_weekend_rule,
            'business_hours.friday.is_weekend' => $business_weekend_rule,
            'business_hours.saturday.is_weekend' => $business_weekend_rule,
            'business_hours.sunday.is_weekend' => $business_weekend_rule,
        ]);
        
        $business_hours_keys = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        
        $business_hours = $validated['business_hours'];
        unset($validated['business_hours']);
        
        if(!empty($validated['assign_worker'])){
            $assign_worker = array_keys($validated['assign_worker']);
            unset($validated['assign_worker']);
            
            $workers = Worker::whereIn('id', $assign_worker)->get();
            if(count($workers) != count($assign_worker))
                return back()->withErrors(['assign_worker_count', 'You trying assign not existing workers']);
        }
        
        $default_start_time = '10:00';
        $default_end_time = '20:00';
        
        $json_business_hours = [];
        foreach($business_hours_keys as $key){
            $json_business_hours[] = [
                'weekday' => $key,
                'is_weekend' => !empty($business_hours[$key]['is_weekend']) ? true : (in_array($key, ['saturday','sunday']) ? true : false),
                'start' => !empty($business_hours[$key]['start_hour']) ? $business_hours[$key]['start_hour'] : $default_start_time,
                'end' => !empty($business_hours[$key]['end_hour']) ? $business_hours[$key]['end_hour'] : $default_end_time,
            ];
        }
        
        $validated['business_hours'] = json_encode($json_business_hours);
        $validated['user_id'] = auth()->user()->id;
        
        if(($hall = Hall::create($validated)) && !empty($assign_worker))
            foreach($assign_worker as $worker_id)
                $hall->workers()->attach($worker_id);
        
        return redirect()->route('dashboard.hall.index');
    }
    
    /**
     * Gets business hours.
     *
     * @return array
     */
    protected function getBusinessHours($request)
    {
        dd($request->post('business_hours'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $hall = Hall::where("id", $id);
        
        if($request->has('with_workers'))
            $hall->with('workers');
        if($request->has('with_suspension'))
            $hall->with('suspension');
            
        $hall = $hall->first();
        
        if($request->wantsJson()){                
            $hall->makeVisible(['business_hours']);
            return response()->json($hall);
        }
        return view('dashboard.hall.show', [
            'hall' => $hall
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
        $hall = Hall::find($id);
        
        $business_hours_raw = json_decode($hall->business_hours);
        $business_hours = [];
        $all_days_closed = true;
        $all_days_opened = true;
        $count_opened_days = 0;
        foreach($business_hours_raw as $itm){
            $business_hours[$itm->weekday]['start_hour'] = $itm->start;
            $business_hours[$itm->weekday]['end_hour'] = $itm->end;
            if(is_bool($itm->is_weekend) && $itm->is_weekend){
                $business_hours[$itm->weekday]['is_weekend'] = 'on';
                $all_days_opened = false;
            }else{
                $all_days_closed = false;
                $count_opened_days++;
            }
        }
        
        $assign_workers = [];
        // dd($assign_worker);
        foreach($hall->workers as $itm){
            $assign_workers[$itm->id] = 'on';
            // dump($itm->id);
        }
        
        // dd(1111);
        
        return view('dashboard.hall.create', [
            'hall' => $hall,
            'business_hours' => $business_hours,
            'all_days_closed' => $all_days_closed,
            'all_days_opened' => $all_days_opened,
            'count_opened_days' => $count_opened_days,
            'assign_workers' => $assign_workers,
            'suspension' => $hall->suspension,
            // 'is_suspended' => !empty($hall->suspension) ? ToogleSuspension::isSuspended($hall->suspension) : false,
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
        $business_hour_rule = 'nullable|string|max:10|regex:/\d{2}:\d{2}/i';
        $business_weekend_rule = 'in:on';
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'max:1000',
            'short_description' => 'max:255',
            'notice' => 'max:1000',
            'country' => 'max:255',
            'town' => 'max:255',
            'street' => 'max:255',
            // 'is_closed' => 'required|in:0,1',
            
            'status' => 'required|in:disable,complete,period',
            'from' => 'required_if:status,==,period|nullable|string|max:20|regex:/\d{2}-\d{2}-\d{4}/i',
            'to' => 'required_if:status,==,period|nullable|string|max:20|regex:/\d{2}-\d{2}-\d{4}/i',
            
            'assign_worker' => 'nullable|array',
            'business_hours' => 'required|array',
            'business_hours.monday.start_hour' => $business_hour_rule,
            'business_hours.monday.end_hour' => $business_hour_rule,
            'business_hours.tuesday.start_hour' => $business_hour_rule,
            'business_hours.tuesday.end_hour' => $business_hour_rule,
            'business_hours.wednesday.start_hour' => $business_hour_rule,
            'business_hours.wednesday.end_hour' => $business_hour_rule,
            'business_hours.thursday.start_hour' => $business_hour_rule,
            'business_hours.thursday.end_hour' => $business_hour_rule,
            'business_hours.friday.start_hour' => $business_hour_rule,
            'business_hours.friday.end_hour' => $business_hour_rule,
            'business_hours.saturday.start_hour' => $business_hour_rule,
            'business_hours.saturday.end_hour' => $business_hour_rule,
            'business_hours.sunday.start_hour' => $business_hour_rule,
            'business_hours.sunday.end_hour' => $business_hour_rule,
            'business_hours.monday.is_weekend' => $business_weekend_rule,
            'business_hours.tuesday.is_weekend' => $business_weekend_rule,
            'business_hours.wednesday.is_weekend' => $business_weekend_rule,
            'business_hours.thursday.is_weekend' => $business_weekend_rule,
            'business_hours.friday.is_weekend' => $business_weekend_rule,
            'business_hours.saturday.is_weekend' => $business_weekend_rule,
            'business_hours.sunday.is_weekend' => $business_weekend_rule,
        ]);
        
        // dd($validated);
        
        $business_hours_keys = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        
        $business_hours = $validated['business_hours'];
        if(!empty($validated['assign_worker']))
            $assign_worker = array_keys($validated['assign_worker']);
        unset($validated['business_hours'], $validated['assign_worker']);
        
        // dd($business_hours);
        
        $suspension = [
            'status' => $validated['status'],
            'from' => !empty($validated['from']) ? $validated['from'] : null,
            'to' => !empty($validated['to']) ? $validated['to'] : null,
        ];
        unset($validated['status'], $validated['from'], $validated['to']);
        
        if(!empty($validated['assign_worker'])){
            $workers = Worker::whereIn('id', $assign_worker)->get();
            if(count($workers) != count($assign_worker))
                return back()->withErrors(['assign_worker_count', 'You trying assign not existing workers']);
        }
        
        // dd($assign_worker, $workers, count($workers));
        
        $default_start_time = '10:00';
        $default_end_time = '20:00';
        
        $json_business_hours = [];
        foreach($business_hours_keys as $key){
            $json_business_hours[] = [
                'weekday' => $key,
                // 'is_weekend' => !empty($business_hours[$key]['is_weekend']) ? true : (in_array($key, ['saturday','sunday']) ? true : false),
                'is_weekend' => !empty($business_hours[$key]['is_weekend']),
                'start' => !empty($business_hours[$key]['start_hour']) ? $business_hours[$key]['start_hour'] : $default_start_time,
                'end' => !empty($business_hours[$key]['end_hour']) ? $business_hours[$key]['end_hour'] : $default_end_time,
            ];
        }
        
        $validated['business_hours'] = json_encode($json_business_hours);
        // dd($validated);
        
        $res = DB::table('halls')
            ->where('id', $id)
            ->update($validated);
        
        // dd($res);
        // if($res){
        //     dd(111);
        $hall = Hall::find($id);    
        $hall->workers()->detach();
        
        if(!empty($assign_worker)){
            foreach($assign_worker as $worker_id){
                $hall->workers()->attach($worker_id);
            }
        }
        
        // dd($suspension);
        
        $toogle_suspension = new ToogleSuspension(
            $suspension['status'],
            $hall,
            $suspension['from'] ?? null,
            $suspension['to'] ?? null
        );
        $toogle_suspension->toogle();
            
        // if($hall = Hall::create($validated)){
        //     $hall->workers()->sync($assign_worker);
        //     // foreach($assign_worker as $worker_id){
        //     //     $hall->workers()->attach($worker_id);
        //     //     // $hall_worker_assignment = HallWorkerAssignment::updateOrCreate(
        //     //     //     ['hall_id' => $hall->id, 'worker_id' => $itm],
        //     //     //     ['hall_id' => $hall->id, 'worker_id' => $itm]
        //     //     // );
        //     // }
        //     // HallWorkerAssignment
        // }
        
        // dd(111111);
        // $this->getBusinessHours($request);
        // return redirect()->route('dashboard.hall.index');
        // return redirect()->back()->with('success', 'Data saccessfuly saved!');
        $route_params = ['hall' => $id];
        if($request->has('tab'))
            $route_params['tab'] = $request->tab;
        return redirect()->route('dashboard.hall.edit', $route_params)->with('success', 'Data saccessfuly saved!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Hall::where('id', $id)
            ->where('is_deleted', 0)
            ->update(['is_deleted' => 1]);
            
        return redirect()->route('dashboard.hall.index');
    }
}
