<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\PersonController as Controller;
use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Phone;
use App\Models\Hall;
// use App\Models\WorkerSuspension;
use App\Providers\RouteServiceProvider;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Classes\PhonePicker\Enums\Types as PhoneTypes;
use App\Classes\PhonePicker\Enums\IndexPrefixes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ViewErrorBag;
use App\Classes\Suspension\ToogleSuspension;
use App\Classes\Setting\Enums\Keys as SettingKeys;
use App\Classes\Enums\Weekdays;

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
    public function dataList(){
        $workers = Worker::select([
            DB::raw("workers.`id` as `id`"),
            DB::raw("workers.`first_name`"),
            DB::raw("workers.`last_name`"),
            DB::raw("CONCAT(COALESCE(workers.`first_name`,''),IF(ISNULL(workers.`first_name`) || ISNULL(workers.`last_name`), '', ' '),COALESCE(workers.`last_name`,'')) as `full_name`"),
            'email',
            DB::raw("workers.`created_at` as `created_at`"),
            DB::raw("(SELECT COUNT(*) FROM `suspensions` WHERE `suspensionable_type` = 'worker' AND `suspensionable_id` = workers.`id`) as sort_status"),
            DB::raw("(SELECT COUNT(*) FROM `suspensions` WHERE `suspensionable_type` = 'worker' AND `suspensionable_id` = workers.`id` AND `from` IS NULL AND `to` IS NULL) as sort_status_2"),
            DB::raw("
                (
                    SELECT COUNT(*)
                    FROM `hall_worker` hw
                    WHERE
                        hw.`worker_id` = workers.`id` AND
                        (
                            SELECT COUNT(*)
                            FROM `halls` h
                            WHERE
                                h.id = hw.`hall_id` AND
                                h.is_deleted = 0
                        )
                ) as halls_count
            "),
        ])
        ->with('halls')->with('suspension')->where('is_deleted', 0);
        
        return Datatables::eloquent($workers)->filterColumn('full_name', function($query, $keyword) {
                    $sql = "CONCAT(first_name,' ',last_name)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('created_at', function($query, $keyword) {
                    $sql = "DATE_FORMAT(workers.`created_at`, '%d/%m/%Y') like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->orderColumn('status', function ($query, $order) {
                    $query->orderBy('sort_status', $order);
                    $query->orderBy('sort_status_2', $order);
                })
                ->orderColumn('halls_count', function ($query, $order) {
                    $query->orderBy('halls_count', $order);
                })
                ->toJson(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        // dd(\Lang::get('validation'));
        // dd(\Lang::get('validation.email'));
        // dd(app());
        // dd(app('path.lang'));
        
        $phones = \PhonePicker::getAllForVue();
        
        $tab_errors = \Session::has('tab_errors') ? \Session::get('tab_errors') : null;
        
        $business_hours = \Setting::getOrPlaceholder(SettingKeys::WORKER_DEFAULT_BUSINESS_HOURS, true);
        
        // dd(\Suspension::getOldForVue());
        
        return view('dashboard.worker.create', [
            'phone_types' => PhoneTypes::all(),
            'old_suspension' => \Suspension::getOldForVue(),
            'index_prefixes' => \PhonePicker::getIndexPrefixesForVue(),
            'phones' => !empty($phones) ? $phones : null,
            'tab_errors' => $tab_errors,
            'business_hours' => $business_hours['data'],
            'count_weekends' => $business_hours['count_weekends'],
            'count_workdays' => $business_hours['count_workdays'],
            'validation_messages' => \Lang::get('validation'),
        ]);
    }
    
    public function mainValidationRules(){
        $business_hour_rule = 'nullable|string|max:10|regex:/\d{2}:\d{2}/i';
        $business_weekend_rule = 'in:on';
        
        return [
            //Main rules
            'first_name' => 'required|max:255',
            'last_name' => 'max:255',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|regex:/^\+[0-9]{3,20}$/i',
            'birthdate' => 'nullable|regex:/\d{4}-\d{2}-\d{2}/i',
            'country' => 'max:255',
            'town' => 'max:255',
            'street' => 'max:255',
            
            //Suspension rules
            'status' => 'required|in:disable,complete,period',
            'suspend_from' => 'required_if:status,==,period|nullable|string|max:20|regex:/\d{2}-\d{2}-\d{4}/i',
            'suspend_to' => 'required_if:status,==,period|nullable|string|max:20|regex:/\d{2}-\d{2}-\d{4}/i',
            
            //Assign halls rule
            'assign_item' => 'nullable|array',
            
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
    
    public function checkEmail(Request $request, $id = null){
        
        $validate_rules = [
            'email' => [
                'required', 'email', ['max', 255], Rule::unique('workers')->where(function($query) use ($id) {
                    $query->where('is_deleted', '=', '0');
                    if(!is_null($id)){
                        // $query->where('is_deleted', '=', '0')->where('id', '!=', $id);
                        $query->where('id', '!=', $id);
                    }
                    // else{
                    //     $query->where('is_deleted', '=', '0');
                    // }
                    // $query->where('is_deleted', '=', '0')->where('id', '!=', $id);
                    // $query->where('is_deleted', '=', '0');
                })
            ]
        ];
        
        $validator = Validator::make($request->all(), $validate_rules);
        if($validator->fails())
            return response()->json(false);
        return response()->json(true);
            // return 'ddddddd';
            // return false;
        // return 'ddddddd';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        
        $validate_rules = array_merge($this->mainValidationRules(), [
            'email' => [
                'required', 'email', ['max', 255], Rule::unique('workers')->where(function($query) {
                    // $query->where('is_deleted', '=', '0')->where('id', '!=', $id);
                    $query->where('is_deleted', '=', '0');
                })
            ],
            'password' => 'required|max:255',
            'password_confirm' => 'required|same:password',
        ]);
        
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
        
        if(!empty($validated['assign_item'])){
            $assign_item = array_keys($validated['assign_item']);
            unset($validated['assign_item']);
            $halls = Hall::whereIn('id', $assign_item)->get();
            if(count($halls) != count($assign_item))
                return back()->withErrors(['assign_item_count', 'You trying assign not existing items']);
        }
        
        $validated['business_hours'] = json_encode($validated['business_hours']);
        
        $validated['user_id'] = auth()->user()->id;
        $validated['password'] = Hash::make($validated['password']);
        
        $validated['birthdate'] = $this->parseToCorrectDBDate($validated['birthdate']);
        
        // dd($validated);
        
        $worker = Worker::create($validated);
        if(!empty($assign_item)){
            foreach($assign_item as $item_id){
                $worker->halls()->attach($item_id);
            }
        }
        \PhonePicker::saveAllPhones($request, $worker);
        
        return redirect()->route('dashboard.worker.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id){
        
        $model = Worker::where("id", $id);
        
        if($request->has('with_phones'))
            $model->with('phones');
        if($request->has('with_halls'))
            $model->with('halls');
        if($request->has('with_suspension'))
            $model->with('suspension');
            
        $worker = $model->first();
        
        if($request->wantsJson()){                
            $worker->makeVisible(['business_hours']);
            return response()->json($worker);
        }
        return view('dashboard.worker.show', [
            'worker' => $worker
        ]);
    }
    
    protected function getPhones($worker = null){
        
        // $phones = $worker->phones;
        $error_messages = null;
        $errors = session()->get('errors');
        if(!empty($errors))
            $error_messages = $errors->default->messages();
        // dump($errors->default->messages());
        $parsed_phones = [];
        // $parsed_phones_with_id = [];
        if(old('_token') !== null){
            // $phones_arr = $phones->asArray();
            for($i = 0; $i < 10; $i++){
                
                if(!empty(old('phone_' . $i))){
                    $parsed_phone = [
                        'phone' => [
                            'value' => old('phone_' . $i),
                            'error' => !empty($error_messages['phone_' . $i]) ? $error_messages['phone_' . $i] : null,
                        ],
                        'id' => [
                            'value' => old('phone_id_' . $i),
                            'error' => !empty($error_messages['phone_' . $i]) ? $error_messages['phone_' . $i] : null,
                        ],
                        // 'phone' => old('phone_' . $i),
                        'id' => old('phone_id_' . $i),
                        'type' => old('phone_type_' . $i),
                        'custom_type' => old('custom_phone_type_' . $i),
                    ];
                    // if(!empty($parsed_phone['id']) && is_numeric($parsed_phone['id'])){
                    //     $parsed_phones_with_id[$parsed_phone['id']] = $parsed_phone;
                    // }else{
                        $parsed_phones[] = $parsed_phone;
                    // }
                }
                
                // $phone_parsed = [
                //     'phone' => $phone->phone,
                //     'id' => $phone->id,
                // ];
                // if(!in_array($phone->type, PhoneTypes::all())){
                //     $phone_parsed['type'] = 'custom';
                //     $phone_parsed['custom_type'] = $phone->type;
                // }else{
                //     $phone_parsed['type'] = $phone->type;
                //     $phone_parsed['custom_type'] = null;
                // }
                // $phones_parsed[] = $phone_parsed;
            }
            
            
        }else if(!is_null($worker) && $worker instanceof Worker && old('_token') === null){
            foreach($worker->phones as $phone){
                $parsed_phone = [
                    'phone' => $phone->phone,
                    'id' => $phone->id,
                ];
                if(!in_array($phone->type, PhoneTypes::all())){
                    $parsed_phone['type'] = 'custom';
                    $parsed_phone['custom_type'] = $phone->type;
                }else{
                    $parsed_phone['type'] = $phone->type;
                    $parsed_phone['custom_type'] = null;
                }
                $parsed_phones[] = $parsed_phone;
            }
        }
        
        return $parsed_phones;
        
        // if(!empty($phones)){
        // 
        // 
        //     if(old('_token') === null){
        //         $phone_parsed = [
        //             'phone' => $phone->phone,
        //             'id' => $phone->id,
        //         ];
        //         if(!in_array($phone->type, PhoneTypes::all())){
        //             $phone_parsed['type'] = 'custom';
        //             $phone_parsed['custom_type'] = $phone->type;
        //         }else{
        //             $phone_parsed['type'] = $phone->type;
        //             $phone_parsed['custom_type'] = null;
        //         }
        //         $phones_parsed[] = $phone_parsed;
        //     }
        // }
        // 
        // return $phone_parsed;
        // 
        // 
        // 
        // 
        // $old_phones = [];
        // $old_phones_by_id = [];
        // $old_phones_new = [];
        // for($i = 0; $i < 10; $i++){
        //     $old_phone = [];
        //     if(!empty(old('phone_' . $i))){
        //         $phone = old('phone_' . $i);
        //         $phone_id = old('phone_id_' . $i);
        //         $phone_type = old('phone_type_' . $i);
        //         $custom_phone_type = old('custom_phone_type_' . $i);
        //         $old_phone = [
        //             'phone' => $phone,
        //             'id' => $phone_id,
        //             'type' => $phone_type,
        //             'custom_type' => $custom_phone_type,
        //         ];
        //         $old_phones[] = $old_phone;
        //         if(!empty($phone_id)){
        //             $old_phones_by_id[$phone_id] = $old_phone;
        //         }else{
        //             $old_phones_new[] = $old_phone;
        //         }
        //     }
        // }
        // 
        // //Set values of phones for VUE which sent from inputs that already in DB
        // $phones = $worker->phones;
        // if(!empty($phones)){
        //     $phones_parsed = [];
        //     foreach($phones as $phone){
        //         // dd($phone->phone);
        //         if(!empty($old_phones_by_id[$phone->id])){
        //             dump($phone->id);
        //             $phones_parsed[] = $old_phones_by_id[$phone->id];
        //             // unset($old_phones_by_id[$phone->id]);
        //             continue;
        //         }
        //         if(old('_token') === null){
        //             $phone_parsed = [
        //                 'phone' => $phone->phone,
        //                 'id' => $phone->id,
        //             ];
        //             if(!in_array($phone->type, PhoneTypes::all())){
        //                 $phone_parsed['type'] = 'custom';
        //                 $phone_parsed['custom_type'] = $phone->type;
        //             }else{
        //                 $phone_parsed['type'] = $phone->type;
        //                 $phone_parsed['custom_type'] = null;
        //             }
        //             $phones_parsed[] = $phone_parsed;
        //         }
        //     }
        // }
        // 
        // //Set new added phones for VUE which not persisted yet in DB
        // if(!empty($old_phones_new))
        //     foreach($old_phones_new as $old_phone_new)
        //         $phones_parsed[] = $old_phone_new;
        
        // dd($phones_parsed);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        
        $worker = Worker::find($id);
        
        // dd($worker->halls);
        $assign_halls = [];
        foreach($worker->halls as $itm)
            $assign_halls[$itm->id] = 'on';
        
        $phones = \PhonePicker::getAllForVue($worker);
        
        $business_hours = \Setting::arrangeByKey(
            SettingKeys::WORKER_DEFAULT_BUSINESS_HOURS,
            json_decode($worker->business_hours, true)
        );
        
        // dd($worker->suspension);
        // dd(ToogleSuspension::parseSuspensionDB($worker->suspension));
        
        return view('dashboard.worker.create', [
            'worker' => $worker,
            // 'suspension' => ToogleSuspension::parseSuspensionDB($worker->suspension),
            'suspension' => $worker->suspension,
            'old_suspension' => \Suspension::getOldForVue(),
            // 'notice' => \Suspension::getNotice($worker->suspension),
            'phone_types' => PhoneTypes::all(),
            'index_prefixes' => \PhonePicker::getIndexPrefixesForVue(),
            'phones' => !empty($phones) ? $phones : null,
            'current_phones' => !empty($worker->phones) ? $worker->phones : null,
            'tab_errors' => \Session::has('tab_errors') ? \Session::get('tab_errors') : null,
            'business_hours' => $business_hours['data'],
            'count_weekends' => $business_hours['count_weekends'],
            'count_workdays' => $business_hours['count_workdays'],
            'assign_halls' => $assign_halls,
            'validation_messages' => \Lang::get('validation'),
        ]);
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
    
        $worker = Worker::find($id);
    
        if(empty($worker))
            return response()->json([
                'status' => 'fail',
                'msg' => 'Worker with `id` - ' . $id . ' does not exist'
            ]);
        
        $toogle_suspension = new ToogleSuspension(
            $validated['type'],
            $worker,
            $validated['from'] ?? null,
            $validated['to'] ?? null
        );
        $toogle_suspension->toogle();
        
        return response()->json([
            'status' => 'success',
            'worker' => $worker,
            'type' => $validated['type'],
            'from' => $toogle_suspension->getFromDate(),
            'to' => $toogle_suspension->getToDate(),
        ]);
    }
    
    /**
     * Toggle worker suspension.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function old_toggleSuspension(Request $request, $id){
        
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
     protected function formatDate($date){
         $arr = explode('-', $date);
         return $arr[2] . '-' . $arr[1] . '-' . $arr[0];
     }
    
    /**
     * Show the form for editing the password.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPassword($id){
        // dd(111);
        $worker = Worker::find($id);
        return view('dashboard.worker.create_password', [
            'worker' => $worker
        ]);
    }
    
    protected function getErrorsCountsPerTab($error_messages){
        $attributes_per_tab = [
            "main" => ['email','first_name','last_name','gender','birthdate'],
            "address" => ['country','town','street'],
            "pass" => ['password','password_confirm'],
        ];
        
        $main_errors_count = 0;
        $address_errors_count = 0;
        $password_errors_count = 0;
        $phones_errors_count = 0;
        foreach($error_messages as $k => $v){
            if(in_array($k, $attributes_per_tab['main']))
                $main_errors_count++;
            if(in_array($k, $attributes_per_tab['address']))
                $address_errors_count++;
            if(in_array($k, $attributes_per_tab['pass']))
                $password_errors_count++;
            if(str_starts_with($k, 'phone'))
                $phones_errors_count++;
        }
        
        return [
            "phones" => $phones_errors_count,
            "main" => $main_errors_count,
            "address" => $address_errors_count,
            "pass" => $password_errors_count,
        ];
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        
        $validate_rules = array_merge($this->mainValidationRules(), [
            'email' => [
                'required', 'email', ['max', 255], Rule::unique('workers')->where(function($query) use ($id) {
                    $query->where('is_deleted', '=', '0')->where('id', '!=', $id);
                })
            ],
            'password' => 'max:255',
            'password_confirm' => 'required_with:password|same:password',
        ]);
        
        $phone_rules_and_messages = \PhonePicker::getPhonesValidetionRulesAndCustomMessages($request);
        $messages = $phone_rules_and_messages['messages'];
        $validate_rules = array_merge($validate_rules, $phone_rules_and_messages['rules']);
        
        $validator = Validator::make($request->all(), $validate_rules, $messages);
        if ($validator->fails()){
            $error_messages = $validator->errors()->messages();
            $tab_errors = $this->getErrorsCountsPerTab($error_messages);
            
            // dd($validator->errors());
            
            return back()->with([
                'tab_errors' => $tab_errors
            ])->withInput($request->all())->withErrors($validator->errors());
        }
        
        $validated = $validator->valid();
        
        // Set suspension variable for further applying a proper suspension
        if(!empty($validated['status']))
            $suspension = [
                'status' => $validated['status'],
                'from' => !empty($validated['suspend_from']) ? $validated['suspend_from'] : null,
                'to' => !empty($validated['suspend_to']) ? $validated['suspend_to'] : null,
            ];
        
        // Set suspension variable for further applying a proper suspension
        foreach($validated as $k => $v){
            if(in_array($k, ['_token', '_method', 'tab', 'password_confirm', 'status', 'suspend_from', 'suspend_to'])){
                unset($validated[$k]);
            }else if(str_starts_with($k, "phone")){
                unset($validated[$k]);
            }else if(str_starts_with($k, "custom_phone")){
                unset($validated[$k]);
            }
        }
        
        // Apply hashing to password
        if(!empty($validated['password'])){
            $validated['password'] = Hash::make($validated['password']);
        }else{
            unset($validated['password']);
        }
        
        // Fix birthdate to right format
        if(!empty($validated['birthdate']))
            $validated['birthdate'] = $this->parseToCorrectDBDate($validated['birthdate']);
        
        // Fix birthdate to right format
        if(!empty($validated['assign_item'])){
            $assign_item = array_keys($validated['assign_item']);
            unset($validated['assign_item']);
            $halls = Hall::whereIn('id', $assign_item)->get();
            if(count($halls) != count($assign_item))
                return back()->withErrors(['assign_item_count', 'You trying assign not existing items']);
        }
        
        DB::table('workers')
            ->where('id', $id)
            ->update($validated);
        
        $worker = Worker::find($id);
        
        if(!empty($suspension)){
            $toogle_suspension = new ToogleSuspension(
                $suspension['status'],
                $worker,
                $suspension['from'] ?? null,
                $suspension['to'] ?? null
            );
            $toogle_suspension->toogle();
        }
        
        $worker->halls()->detach();
        
        if(!empty($assign_item)){
            foreach($assign_item as $item_id){
                $worker->halls()->attach($item_id);
            }
        }
        
        $route_params = ['worker' => $id];
        if($request->has('tab'))
            $route_params['tab'] = $request->tab;
        
        \PhonePicker::saveAllPhones($request, $worker);
        
        return redirect()->route('dashboard.worker.edit', $route_params)->with('success', 'Data saccessfuly saved!');
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
        // $worker = Worker::where('id', $id)
        //     ->where('is_deleted', 0)
        // $worker->halls()->detach();
        Worker::where('id', $id)
            ->where('is_deleted', 0)
            ->update(['is_deleted' => 1]);
            
        return redirect()->route('dashboard.worker.index');
        // dd($id);
    }
}
