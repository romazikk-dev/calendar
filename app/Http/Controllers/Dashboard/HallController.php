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
        $halls = Hall::select([
            DB::raw("halls.`id`"),
            DB::raw("halls.`title`"),
            DB::raw("halls.`created_at`"),
            DB::raw("(SELECT COUNT(*) FROM hall_worker WHERE hall_worker.`hall_id` = halls.`id`) as `workers_count`"),
            DB::raw("(SELECT COUNT(*) FROM `suspensions` WHERE `suspensionable_type` = 'hall' AND `suspensionable_id` = halls.`id`) as sort_status"),
            DB::raw("(SELECT COUNT(*) FROM `suspensions` WHERE `suspensionable_type` = 'hall' AND `suspensionable_id` = halls.`id` AND `from` IS NULL AND `to` IS NULL) as sort_status_2"),
        ])
        ->with('suspension')->where('is_deleted', 0);
        
        return Datatables::eloquent($halls)->orderColumn('status', function ($query, $order) {
                    $query->orderBy('sort_status', $order);
                    $query->orderBy('sort_status_2', $order);
                })
                ->orderColumn('workers_count', function ($query, $order) {
                    $query->orderBy('workers_count', $order);
                })
                ->toJson(true);
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
        
        if(old('business_hours')){
            $business_hours = \Setting::arrangeByKey(
                SettingKeys::WORKER_DEFAULT_BUSINESS_HOURS,
                old('business_hours')
            );
        }else{
            $business_hours = \Setting::getOrPlaceholder(SettingKeys::DEFAULT_BUSINESS_HOURS, true);
        }
        
        $phones = \PhonePicker::getAllForVue();
        $tab_errors = \Session::has('tab_errors') ? \Session::get('tab_errors') : null;
        
        return view('dashboard.hall.create', [
            'business_hours' => $business_hours['data'],
            'count_weekends' => $business_hours['count_weekends'],
            'count_workdays' => $business_hours['count_workdays'],
            'old_suspension' => \Suspension::getOldForVue(),
            'phone_types' => PhoneTypes::all(),
            'index_prefixes' => \PhonePicker::getIndexPrefixesForVue(),
            'phones' => !empty($phones) ? $phones : null,
            'tab_errors' => $tab_errors,
            'assign_worker' => old('assign_worker') ? old('assign_worker') : null,
            'validation_messages' => \Lang::get('validation'),
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
        
        // dd($validate_rules);
        
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
        
        // dd($validated);
        
        $validated['business_hours'] = json_encode($validated['business_hours']);
        
        if(!empty($validated['assign_workers'])){
            $assign_workers = array_keys($validated['assign_workers']);
            // unset($validated['assign_worker']);
            $workers = Worker::whereIn('id', $assign_workers)->get();
            if(count($workers) != count($assign_workers))
                return back()->withErrors(['assign_worker_count', 'You trying assign to hall not existing workers']);
        }
        
        $validated['user_id'] = auth()->user()->id;
        
        if(($hall = Hall::create($validated)) && !empty($assign_workers))
            foreach($assign_workers as $worker_id)
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
        
        if($request->has('with_phones'))
            $hall->with('phones');
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
    public function edit($id){
        $hall = Hall::find($id);
        
        $business_hours = \Setting::arrangeByKey(
            SettingKeys::DEFAULT_BUSINESS_HOURS,
            json_decode($hall->business_hours, true)
        );
        
        $assign_workers = [];
        // dd($assign_workers);
        foreach($hall->workers as $itm){
            $assign_workers[$itm->id] = 'on';
            // dump($itm->id);
        }
        
        // dd($assign_workers);
        
        $phones = \PhonePicker::getAllForVue($hall);
        $current_phones = $hall->phones->toArray();
        // dd($phones);
        $tab_errors = \Session::has('tab_errors') ? \Session::get('tab_errors') : null;
        // dd($assign_workers);
        
        // dd(\Holiday::getAllForVue($hall));
        
        return view('dashboard.hall.create', [
            'hall' => $hall,
            
            'phones' => !empty($phones) ? $phones : null,
            'phone_types' => PhoneTypes::all(),
            'index_prefixes' => \PhonePicker::getIndexPrefixesForVue(),
            'current_phones' => !empty($current_phones) ? $current_phones : null,
            
            'holidays' => \Holiday::getAllForVue($hall),
            
            // business hours
            'business_hours' => $business_hours['data'],
            'count_weekends' => $business_hours['count_weekends'],
            'count_workdays' => $business_hours['count_workdays'],
            
            'assign_workers' => $assign_workers,
            'suspension' => $hall->suspension,
            'old_suspension' => \Suspension::getOldForVue(),
            
            'tab_errors' => $tab_errors,
            'validation_messages' => \Lang::get('validation'),
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
        
        // dd($validated);
        if(!empty($validated['holiday_title'])){
            $holidays = [];
            foreach($validated['holiday_title'] as $k => $v){
                $holiday = [];
                
                if(!empty($validated['holiday_title'][$k])){
                    $holiday['title'] = $validated['holiday_title'][$k];
                }else{
                    continue;
                }
                
                if(!empty($validated['holiday_from'][$k])){
                    // $holiday['from'] = $validated['holiday_from'][$k];
                    $holiday['from'] = \Carbon\Carbon::parse($validated['holiday_from'][$k]);
                }else{
                    continue;
                }
                
                if(!empty($validated['holiday_to'][$k])){
                    // $holiday['to'] = $validated['holiday_to'][$k];
                    $holiday['to'] = \Carbon\Carbon::parse($validated['holiday_to'][$k]);
                }else{
                    continue;
                }
                
                if(!empty($validated['holiday_description'][$k])){
                    $holiday['description'] = $validated['holiday_description'][$k];
                }else{
                    $holiday['description'] = null;
                }
                
                $holidays[] = $holiday;
            }
            
            unset(
                $validated['holiday_title'],
                $validated['holiday_from'],
                $validated['holiday_to'],
                $validated['holiday_description']
            );
        }
        
        $validated['business_hours'] = json_encode($validated['business_hours']);
        
        if(!empty($validated['assign_workers'])){
            $assign_workers = array_keys($validated['assign_workers']);
            unset($validated['assign_workers']);
            $workers = Worker::whereIn('id', $assign_workers)->get();
            if(count($workers) != count($assign_workers))
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
        $hall->holidays()->delete();
        
        if(!empty($assign_workers))
            foreach($assign_workers as $worker_id)
                $hall->workers()->attach($worker_id);
        
        if(!empty($holidays))
            foreach($holidays as $holiday)
                $hall->holidays()->create($holiday);
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
            
        // dd($request->all());
        // dd($route_params);
        
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
        // dd($id);
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
            
            //Holiday rules
            'holiday_title.*' => 'required|max:255',
            'holiday_description.*' => 'max:1000',
            'holiday_from.*' => 'required|max:255|regex:/\d{2}-\d{2}-\d{4}/i',
            'holiday_to.*' => 'required|max:255|regex:/\d{2}-\d{2}-\d{4}/i',
            
            //Suspension rules
            'status' => 'required|in:disable,complete,period',
            'suspend_from' => 'required_if:status,==,period|nullable|string|max:20|regex:/\d{2}-\d{2}-\d{4}/i',
            'suspend_to' => 'required_if:status,==,period|nullable|string|max:20|regex:/\d{2}-\d{2}-\d{4}/i',
            
            //Assign workers rule
            'assign_workers' => 'nullable|array',
            
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
