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
use Illuminate\Support\Facades\Validator;
use App\Classes\PhonePicker\Enums\Types as PhoneTypes;

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
            // DB::raw("halls.`is_closed`"),
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
    // public function create(Request $request)
    public function create(){
        
        // if({{ old('business_hours') ? old('business_hours')['monday']['start_hour'] : '' }})
        // if(!empty($request->old('business_hours')['monday'])){
        //     dd($request->old('business_hours')['monday']);
        // }
        
        // dd(\Suspension::getOldForVue());
        $phones = \PhonePicker::getAllForVue();
        $business_hours = \Setting::getOrPlaceholder(SettingKeys::DEFAULT_BUSINESS_HOURS);
        $tab_errors = \Session::has('tab_errors') ? \Session::get('tab_errors') : null;
        return view('dashboard.hall.create', [
            'business_hours' => $business_hours,
            'old_suspension' => \Suspension::getOldForVue(),
            'phone_types' => PhoneTypes::all(),
            'index_prefixes' => \PhonePicker::getIndexPrefixesForVue(),
            'phones' => !empty($phones) ? $phones : null,
            'tab_errors' => $tab_errors,
        ]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        
        $validate_rules = $this->mainValidationRules();
        
        $rules_and_messages = \PhonePicker::getPhonesValidetionRulesAndCustomMessages($request);
        $messages = $rules_and_messages['messages'];
        $validate_rules = array_merge($validate_rules, $rules_and_messages['rules']);
        
        // $validator = Validator::make($request->all(), $validate_rules, $messages);
        $validator = Validator::make($request->all(), $validate_rules, $messages);
        if($validator->fails()){
            $error_messages = $validator->errors()->messages();
            $tab_errors = $this->getErrorsCountsPerTab($error_messages);
            
            return back()->with([
                'tab_errors' => $tab_errors
            ])->withInput($request->all())->withErrors($validator->errors());
        }
        
        $validated = $validator->valid();
        
        $validated['business_hours'] = json_encode($validated['business_hours']);
        
        if(!empty($validated['assign_worker'])){
            $assign_worker = array_keys($validated['assign_worker']);
            // unset($validated['assign_worker']);
            $workers = Worker::whereIn('id', $assign_worker)->get();
            if(count($workers) != count($assign_worker))
                return back()->withErrors(['assign_worker_count', 'You trying assign to hall not existing workers']);
        }
        
        $validated['user_id'] = auth()->user()->id;
        
        if(($hall = Hall::create($validated)) && !empty($assign_worker))
            foreach($assign_worker as $worker_id)
                $hall->workers()->attach($worker_id);
        
        if(!empty($hall)){
            if(!empty($validated['status'])){
                \Suspension::toogle(
                    $validated['status'],
                    $hall,
                    !empty($validated['suspend_from']) ? $validated['suspend_from'] : null,
                    !empty($validated['suspend_to']) ? $validated['suspend_to'] : null
                );
            }
        }
        
        \PhonePicker::saveAllPhones($request, $hall);
        
        return redirect()->route('dashboard.hall.index');
    }
    
    /**
     * Gets business hours.
     *
     * @return array
     */
    // protected function getBusinessHours($request)
    // {
    //     dd($request->post('business_hours'));
    // }

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
        
        $business_hours = \Setting::arrangeByKey(
            SettingKeys::DEFAULT_BUSINESS_HOURS,
            json_decode($hall->business_hours, true)
        );
        
        $assign_workers = [];
        // dd($assign_worker);
        foreach($hall->workers as $itm){
            $assign_workers[$itm->id] = 'on';
            // dump($itm->id);
        }
        
        $phones = \PhonePicker::getAllForVue($hall);
        $current_phones = $hall->phones->toArray();
        // dd($phones);
        $tab_errors = \Session::has('tab_errors') ? \Session::get('tab_errors') : null;
        // dd($assign_workers);
        
        return view('dashboard.hall.create', [
            'hall' => $hall,
            
            'phones' => !empty($phones) ? $phones : null,
            'phone_types' => PhoneTypes::all(),
            'index_prefixes' => \PhonePicker::getIndexPrefixesForVue(),
            'current_phones' => !empty($current_phones) ? $current_phones : null,
            
            // business hours
            'business_hours' => $business_hours['data'],
            'count_weekends' => $business_hours['count_weekends'],
            'count_workdays' => $business_hours['count_workdays'],
            
            'assign_workers' => $assign_workers,
            'suspension' => $hall->suspension,
            'old_suspension' => \Suspension::getOldForVue(),
            
            'tab_errors' => $tab_errors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        
        $validate_rules = $this->mainValidationRules();
        
        $rules_and_messages = \PhonePicker::getPhonesValidetionRulesAndCustomMessages($request);
        $messages = $rules_and_messages['messages'];
        $validate_rules = array_merge($validate_rules, $rules_and_messages['rules']);
        
        $validator = Validator::make($request->all(), $validate_rules, $messages);
        
        if($validator->fails()){
            $error_messages = $validator->errors()->messages();
            $tab_errors = $this->getErrorsCountsPerTab($error_messages);
            
            return back()->with([
                'tab_errors' => $tab_errors
            ])->withInput($request->all())->withErrors($validator->errors());
        }
        
        $validated = $validator->valid();
        
        $validated['business_hours'] = json_encode($validated['business_hours']);
        
        if(!empty($validated['assign_worker'])){
            $assign_worker = array_keys($validated['assign_worker']);
            unset($validated['assign_worker']);
            $workers = Worker::whereIn('id', $assign_worker)->get();
            if(count($workers) != count($assign_worker))
                return back()->withErrors(['assign_worker_count', 'You trying assign not existing workers']);
        }
        
        // dd($validated);
        
        // Set suspension variable for further applying a proper suspension
        if(!empty($validated['status']))
            $suspension = [
                'status' => $validated['status'],
                'from' => !empty($validated['suspend_from']) ? $validated['suspend_from'] : null,
                'to' => !empty($validated['suspend_to']) ? $validated['suspend_to'] : null,
            ];
        
        // Set suspension variable for further applying a proper suspension
        foreach($validated as $k => $v){
            if(in_array($k, ['_token', '_method', 'tab', 'status', 'suspend_from', 'suspend_to'])){
                unset($validated[$k]);
            }else if(str_starts_with($k, "phone")){
                unset($validated[$k]);
            }else if(str_starts_with($k, "custom_phone")){
                unset($validated[$k]);
            }
        }
        
        // dd($validated);
        
        $res = DB::table('halls')
            ->where('id', $id)
            ->update($validated);
        
        // dd($res);
        // if($res){
        //     dd(111);
        $hall = Hall::find($id);    
        $hall->workers()->detach();
        
        if(!empty($assign_worker))
            foreach($assign_worker as $worker_id)
                $hall->workers()->attach($worker_id);
        
        // dd($suspension);
        
        if(!empty($suspension['status'])){
            // dd($suspension);
            \Suspension::toogle(
                $suspension['status'],
                $hall,
                $suspension['from'] ?? null,
                $suspension['to'] ?? null
            );
        }
        
        \PhonePicker::saveAllPhones($request, $hall);
        
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
        dd($id);
        Hall::where('id', $id)
            ->where('is_deleted', 0)
            ->update(['is_deleted' => 1]);
            
        return redirect()->route('dashboard.hall.index');
    }
    
    protected function getErrorsCountsPerTab($error_messages){
        $attributes_per_tab = [
            "main" => ['title','description','short_description','notice'],
            "address" => ['country','town','street'],
        ];
        
        $main_errors_count = 0;
        $address_errors_count = 0;
        $phones_errors_count = 0;
        foreach($error_messages as $k => $v){
            if(in_array($k, $attributes_per_tab['main']))
                $main_errors_count++;
            if(in_array($k, $attributes_per_tab['address']))
                $address_errors_count++;
            if(str_starts_with($k, 'phone'))
                $phones_errors_count++;
        }
        
        return [
            "phones" => $phones_errors_count,
            "main" => $main_errors_count,
            "address" => $address_errors_count,
        ];
    }
    
    protected function mainValidationRules(){
        $business_hour_rule = 'nullable|string|max:10|regex:/\d{2}:\d{2}/i';
        $business_weekend_rule = 'in:on';
        
        return [
            //Main rules
            'title' => 'required|max:255',
            'description' => 'max:1000',
            'short_description' => 'max:255',
            'notice' => 'max:1000',
            'country' => 'max:255',
            'town' => 'max:255',
            'street' => 'max:255',
            
            //Suspension rules
            'status' => 'required|in:disable,complete,period',
            'suspend_from' => 'required_if:status,==,period|nullable|string|max:20|regex:/\d{2}-\d{2}-\d{4}/i',
            'suspend_to' => 'required_if:status,==,period|nullable|string|max:20|regex:/\d{2}-\d{2}-\d{4}/i',
            
            //Assign workers rule
            'assign_worker' => 'nullable|array',
            
            //Business hours rules
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
        ];
    }
}
