<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\PersonController as Controller;
use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Phone;
use App\Models\WorkerSuspension;
use App\Providers\RouteServiceProvider;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Classes\Enums\PhoneTypes;
use Illuminate\Support\Facades\Validator;

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
    public function create(){
        
        $old_phones = [];
        for($i = 0; $i < 10; $i++){
            $old_phone = [];
            if(!empty(old('phone_' . $i))){
                $phone = old('phone_' . $i);
                $phone_id = old('phone_id_' . $i);
                $phone_type = old('phone_type_' . $i);
                $custom_phone_type = old('custom_phone_type_' . $i);
                $old_phones[] = [
                    'phone' => $phone,
                    'id' => $phone_id,
                    'type' => $phone_type,
                    'custom_type' => $custom_phone_type,
                ];
            }
        }
        
        $tab_errors = \Session::has('tab_errors') ? \Session::get('tab_errors') : null;
            
        return view('dashboard.worker.create', [
            'phone_types' => PhoneTypes::all(),
            'old_phones' => !empty($old_phones) ? $old_phones : null,
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
        
        $validate_rules = [
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
        ];
        
        $messages = [];
        $phone_types = implode(',', PhoneTypes::all());
        $phone_types .= ',custom';
        
        for($i = 0; $i <= 10; $i++){
            // $request->has('phone_' . $k);
            if($request->has('phone_' . $i)){
                $validate_rules['phone_' . $i] = 'nullable|max:30';
                // $validate_rules['phone_id_' . $i] = 'nullable|required_with:phone_' . $i . '|integer';
                $validate_rules['phone_id_' . $i] = 'nullable|integer';
                $validate_rules['phone_type_' . $i] = 'required_with:phone_' . $i . '|in:' . $phone_types . '';
                $validate_rules['custom_phone_type_' . $i] = 'required_if:phone_type_' . $i . ',==,custom|max:30';
                if(!array_key_exists("phone.max", $messages)){
                    $messages["phone_" . $i . ".max"] = 'The phone must not be greater than :max characters.';
                    $messages["custom_phone_type_" . $i . ".required_if"] = 'Field is required when type is custom.';
                }
            }
        }
        
        $validator = Validator::make($request->all(), $validate_rules, $messages);
        if ($validator->fails()){
            $error_messages = $validator->errors()->messages();
            
            $phone_errors = [];
            $phones_errors_count = 0;
            for($i = 0; $i < 10; $i++){
                if(array_key_exists("phone_" . $i, $error_messages) ||
                array_key_exists("phone_id_" . $i, $error_messages) ||
                array_key_exists("phone_type_" . $i, $error_messages) ||
                array_key_exists("custom_phone_type_" . $i, $error_messages)){
                    $phone_errors[$i] = [
                        "phone" => !empty($error_messages["phone_" . $i][0]) ? $error_messages["phone_" . $i][0] : null,
                        "id" => !empty($error_messages["phone_id_" . $i][0]) ? $error_messages["phone_id_" . $i][0] : null,
                        "type" => !empty($error_messages["phone_type_" . $i][0]) ? $error_messages["phone_type_" . $i][0] : null,
                        "custom_type" => !empty($error_messages["custom_phone_type_" . $i][0]) ? $error_messages["custom_phone_type_" . $i][0] : null,
                    ];
                    if(!is_null($phone_errors[$i]["phone"]))
                        $phones_errors_count++;
                    if(!is_null($phone_errors[$i]["custom_type"]))
                        $phones_errors_count++;
                }
            }
                
            $with = [];
            if(!empty($phone_errors))
                $with['phone_errors'] = $phone_errors;
                
            $attributes_per_tab = [
                "main" => ['email','first_name','last_name','gender','birthdate'],
                "address" => ['country','town','street'],
                "password" => ['password','password_confirm'],
            ];
            
            $main_errors_count = 0;
            $address_errors_count = 0;
            $password_errors_count = 0;
            foreach($error_messages as $k => $v){
                if(in_array($k, $attributes_per_tab['main']))
                    $main_errors_count++;
                if(in_array($k, $attributes_per_tab['address']))
                    $address_errors_count++;
                if(in_array($k, $attributes_per_tab['password']))
                    $password_errors_count++;
            }
            
            $with['tab_errors'] = [
                "phones" => $phones_errors_count,
                "main" => $main_errors_count,
                "address" => $address_errors_count,
                "password" => $password_errors_count,
            ];
            
            if(!empty($with))
                return back()->with($with)->withInput($request->all())->withErrors($validator->errors());
            return back()->withInput($request->all())->withErrors($validator->errors());
        }
        
        $validated = $validator->valid();
        
        $validated['user_id'] = auth()->user()->id;
        $validated['password'] = Hash::make($validated['password']);
        
        $validated['birthdate'] = $this->parseToCorrectDBDate($validated['birthdate']);
        
        $worker = Worker::create($validated);
        
        for($i = 0; $i < 10; $i++){
            $phone = $request->get('phone_' . $i);
            if(!empty($phone)){
                $phone_data = ['phone' => $request->get('phone_' . $i)];
                if($request->has('phone_type_' . $i)){
                    $phone_type = $request->get('phone_type_' . $i);
                    $custom_phone_type = $request->has('custom_phone_type_' . $i) ? $request->get('custom_phone_type_' . $i) : null;
                    if($phone_type == 'custom' && !empty($custom_phone_type)){
                        $phone_data['type'] = $custom_phone_type;
                    }else{
                        $phone_data['type'] = $phone_type;
                    }
                }
                $worker->phones()->create($phone_data);
            }
        }
        
        // dd('saved');
        
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
        //Set an old(values sent from inputs) phones
        $old_phones = [];
        $old_phones_by_id = [];
        $old_phones_new = [];
        for($i = 0; $i < 10; $i++){
            $old_phone = [];
            if(!empty(old('phone_' . $i))){
                $phone = old('phone_' . $i);
                $phone_id = old('phone_id_' . $i);
                $phone_type = old('phone_type_' . $i);
                $custom_phone_type = old('custom_phone_type_' . $i);
                $old_phone = [
                    'phone' => $phone,
                    'id' => $phone_id,
                    'type' => $phone_type,
                    'custom_type' => $custom_phone_type,
                ];
                $old_phones[] = $old_phone;
                if(!empty($phone_id)){
                    $old_phones_by_id[$phone_id] = $old_phone;
                }else{
                    $old_phones_new[] = $old_phone;
                }
            }
        }
        
        // dd(111);
        $worker = Worker::find($id);
        // dd($worker);
        
        //Set values of phones for VUE which sent from inputs that already in DB
        $phones = $worker->phones;
        if(!empty($phones)){
            $phones_parsed = [];
            foreach($phones as $phone){
                // dd($phone->phone);
                if(!empty($old_phones_by_id[$phone->id])){
                    dump($phone->id);
                    $phones_parsed[] = $old_phones_by_id[$phone->id];
                    // unset($old_phones_by_id[$phone->id]);
                    continue;
                }
                if(old('_token') === null){
                    $phone_parsed = [
                        'phone' => $phone->phone,
                        'id' => $phone->id,
                    ];
                    if(!in_array($phone->type, PhoneTypes::all())){
                        $phone_parsed['type'] = 'custom';
                        $phone_parsed['custom_type'] = $phone->type;
                    }else{
                        $phone_parsed['type'] = $phone->type;
                        $phone_parsed['custom_type'] = null;
                    }
                    $phones_parsed[] = $phone_parsed;
                }
            }
        }
        
        //Set new added phones for VUE which not persisted yet in DB
        if(!empty($old_phones_new))
            foreach($old_phones_new as $old_phone_new)
                $phones_parsed[] = $old_phone_new;
        
        // dd($phones_parsed);
        
        return view('dashboard.worker.create', [
            'worker' => $worker,
            'phone_types' => PhoneTypes::all(),
            'old_phones' => !empty($old_phones) ? $old_phones : null,
            'phones' => !empty($phones_parsed) ? $phones_parsed : null,
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
        // foreach($request->all() as $k => $v){
        // 
        // }
        
        $validate_rules = [
            'first_name' => 'required|max:255',
            'last_name' => 'max:255',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|regex:/^\+[0-9]{3,20}$/i',
            'birthdate' => 'nullable|regex:/\d{4}-\d{2}-\d{2}/i',
            'country' => 'max:255',
            'town' => 'max:255',
            'street' => 'max:255',
            'email' => [
                'required', 'email', ['max', 255], Rule::unique('workers')->where(function($query) use ($id) {
                    $query->where('is_deleted', '=', '0')->where('id', '!=', $id);
                })
            ],
            'password' => 'max:255',
            'password_confirm' => 'required_with:password|same:password',
        ];
        
        $messages = [];
        $phone_types = implode(',', PhoneTypes::all());
        $phone_types .= ',custom';
        
        for($i = 0; $i <= 10; $i++){
            // $request->has('phone_' . $k);
            if($request->has('phone_' . $i)){
                $validate_rules['phone_' . $i] = 'nullable|max:30';
                // $validate_rules['phone_id_' . $i] = 'nullable|required_with:phone_' . $i . '|integer';
                $validate_rules['phone_id_' . $i] = 'nullable|integer';
                $validate_rules['phone_type_' . $i] = 'required_with:phone_' . $i . '|in:' . $phone_types . '';
                $validate_rules['custom_phone_type_' . $i] = 'required_if:phone_type_' . $i . ',==,custom|max:30';
                if(!array_key_exists("phone.max", $messages)){
                    $messages["phone_" . $i . ".max"] = 'The phone must not be greater than :max characters.';
                    $messages["custom_phone_type_" . $i . ".required_if"] = 'Field is required when type is custom.';
                }
            }
        }
        
        $validator = Validator::make($request->all(), $validate_rules, $messages);
        if ($validator->fails()){
            
            // dd($validator->errors()->messages());
            
            $error_messages = $validator->errors()->messages();
            
            $phone_errors = [];
            $phones_errors_count = 0;
            $input = $request->all();
            
            $k = 0;
            for($i = 0; $i < 10; $i++){
                // if(!empty($input["phone_" . $i])){
                if($request->has("phone_" . $i))
                    $phone_input = $request->get("phone_" . $i);
                if(!empty($phone_input)){
                    
                    if(array_key_exists("phone_" . $i, $error_messages) ||
                    array_key_exists("phone_id_" . $i, $error_messages) ||
                    array_key_exists("phone_type_" . $i, $error_messages) ||
                    array_key_exists("custom_phone_type_" . $i, $error_messages)){
                        
                        $phone_errors[$k] = [
                            "phone" => !empty($error_messages["phone_" . $i][0]) ? $error_messages["phone_" . $i][0] : null,
                            "id" => !empty($error_messages["phone_id_" . $i][0]) ? $error_messages["phone_id_" . $i][0] : null,
                            "type" => !empty($error_messages["phone_type_" . $i][0]) ? $error_messages["phone_type_" . $i][0] : null,
                            "custom_type" => !empty($error_messages["custom_phone_type_" . $i][0]) ? $error_messages["custom_phone_type_" . $i][0] : null,
                        ];
                        if(!is_null($phone_errors[$k]["phone"]))
                            $phones_errors_count++;
                        if(!is_null($phone_errors[$k]["custom_type"]))
                            $phones_errors_count++;
                    }
                    $k++;
                    
                }
            }
            
            // dd($phones_errors_count);
                
            $with = [];
            if(!empty($phone_errors)){
                $with['phone_errors'] = $phone_errors;
                // $with['phones_errors_count'] = $phones_errors_count;
                // dd($phone_errors);
            }
                
            $attributes_per_tab = [
                "main" => ['email','first_name','last_name','gender','birthdate'],
                "address" => ['country','town','street'],
                "password" => ['password','password_confirm'],
            ];
            
            $main_errors_count = 0;
            $address_errors_count = 0;
            $password_errors_count = 0;
            foreach($error_messages as $k => $v){
                if(in_array($k, $attributes_per_tab['main']))
                    $main_errors_count++;
                if(in_array($k, $attributes_per_tab['address']))
                    $address_errors_count++;
                if(in_array($k, $attributes_per_tab['password']))
                    $password_errors_count++;
            }
            
            $with['tab_errors'] = [
                "phones" => $phones_errors_count,
                "main" => $main_errors_count,
                "address" => $address_errors_count,
                "password" => $password_errors_count,
            ];
            
            if(!empty($with))
                return back()->with($with)->withInput($request->all())->withErrors($validator->errors());
            return back()->withInput($request->all())->withErrors($validator->errors());
        }
        
        $validated = $validator->valid();
        foreach($validated as $k => $v){
            if(in_array($k, ['_token', '_method', 'tab', 'password_confirm'])){
                unset($validated[$k]);
            }else if(str_starts_with($k, "phone")){
                unset($validated[$k]);
            }else if(str_starts_with($k, "custom_phone")){
                unset($validated[$k]);
            }
        }
        
        // dd($validated);
        
        if(!empty($validated['password'])){
            $validated['password'] = Hash::make($validated['password']);
        }else{
            unset($validated['password']);
        }
        
        if(!empty($validated['birthdate']))
            $validated['birthdate'] = $this->parseToCorrectDBDate($validated['birthdate']);
        
        DB::table('workers')
            ->where('id', $id)
            ->update($validated);
            
        // dd($worker);
        
        $worker = Worker::find($id);
        $worker->phones()->delete();
        
        $route_params = ['worker' => $id];
        if($request->has('tab'))
            $route_params['tab'] = $request->tab;
            
        for($i = 0; $i < 10; $i++){
            $phone = $request->get('phone_' . $i);
            if(!empty($phone)){
                $phone_data = ['phone' => $request->get('phone_' . $i)];
                if($request->has('phone_type_' . $i)){
                    $phone_type = $request->get('phone_type_' . $i);
                    $custom_phone_type = $request->has('custom_phone_type_' . $i) ? $request->get('custom_phone_type_' . $i) : null;
                    if($phone_type == 'custom' && !empty($custom_phone_type)){
                        $phone_data['type'] = $custom_phone_type;
                    }else{
                        $phone_data['type'] = $phone_type;
                    }
                }
                $worker->phones()->create($phone_data);
            }
        }
        
        // dd($request->has('tab'));
        // dd($route_params);
        // dd(route('dashboard.worker.edit', $route_params));
        
        // return redirect()->back()->with('success', 'Data saccessfuly saved!');
        
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
        Worker::where('id', $id)
            ->where('is_deleted', 0)
            ->update(['is_deleted' => 1]);
            
        return redirect()->route('dashboard.worker.index');
        // dd($id);
    }
}
