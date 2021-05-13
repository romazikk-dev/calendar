<?php

namespace App\Http\Controllers\Dashboard;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\PersonController as Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Classes\PhonePicker\Enums\Types as PhoneTypes;
use App\Classes\PhonePicker\Enums\IndexPrefixes;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        // dd(11);
        return view('dashboard.client.index');
    }
    
    /**
     * Toggle suspension.
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
    
        $client = Client::find($id);
    
        if(empty($client))
            return response()->json([
                'status' => 'fail',
                'msg' => 'Worker with `id` - ' . $id . ' does not exist'
            ]);
        
        if(!empty($validated['type']))
            \Suspension::toogle(
                $validated['type'],
                $client,
                !empty($validated['from']) ? $validated['from'] : null,
                !empty($validated['to']) ? $validated['to'] : null
            );
        
        return response()->json([
            'status' => 'success',
            'worker' => $client,
            'type' => $validated['type'],
            'from' => !empty($validated['from']) ? $validated['from'] : null,
            'to' => !empty($validated['to']) ? $validated['to'] : null,
        ]);
    }
    
    /**
     * Return list of clients for ajax request.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataList(){
             
        $сlients = Client::select([
            DB::raw("clients.`id`"),
            DB::raw("clients.`first_name`"),
            DB::raw("clients.`last_name`"),
            DB::raw("CONCAT(clients.`first_name`,' ',clients.`last_name`) as `full_name`"),
            DB::raw("clients.`email`"),
            DB::raw("clients.`created_at`"),
            DB::raw("(SELECT COUNT(*) FROM `suspensions` WHERE `suspensionable_type` = 'client' AND `suspensionable_id` = clients.`id`) as sort_status"),
            DB::raw("(SELECT COUNT(*) FROM `suspensions` WHERE `suspensionable_type` = 'client' AND `suspensionable_id` = clients.`id` AND `from` IS NULL AND `to` IS NULL) as sort_status_2"),
        ])->with('suspension')->where('is_deleted', 0);
        
        return Datatables::eloquent($сlients)->filterColumn('full_name', function($query, $keyword) {
                    $sql = "CONCAT(first_name,' ',last_name)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->orderColumn('status', function ($query, $order) {
                    $query->orderBy('sort_status', $order);
                    $query->orderBy('sort_status_2', $order);
                })
                ->toJson(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('dashboard.client.create');
        
        $phones = \PhonePicker::getAllForVue();
        $tab_errors = \Session::has('tab_errors') ? \Session::get('tab_errors') : null;
        
        return view('dashboard.client.create', [
            'phone_types' => PhoneTypes::all(),
            'old_suspension' => \Suspension::getOldForVue(),
            'index_prefixes' => \PhonePicker::getIndexPrefixesForVue(),
            'phones' => !empty($phones) ? $phones : null,
            'tab_errors' => $tab_errors,
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
        
        $validate_rules = array_merge($this->mainValidationRules(), [
            'email' => [
                'required', 'email', ['max', 255], Rule::unique('clients')->where(function($query) {
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
        
        $validated['user_id'] = auth()->user()->id;
        $validated['password'] = Hash::make($validated['password']);
        $validated['birthdate'] = $this->parseToCorrectDBDate($validated['birthdate']);
        
        $worker = Client::create($validated);
        
        \PhonePicker::saveAllPhones($request, $worker);
        
        return redirect()->route('dashboard.worker.index');
        // $validated = $this->validateData($request);
        // 
        // $validated['birthdate'] = $this->parseToCorrectDBDate($validated['birthdate']);
        // $validated['user_id'] = auth()->user()->id;
        // 
        // Client::create($validated);
        // 
        // return redirect()->route('dashboard.client.index');
    }
    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     $client = Client::find($id);
    //     return view('dashboard.client.show', [
    //         'client' => $client
    //     ]);
    // }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id){
        
        $model = Client::where("id", $id);
        
        if($request->has('with_phones'))
            $model->with('phones');
        if($request->has('with_suspension'))
            $model->with('suspension');
            
        $client = $model->first();
        
        if($request->wantsJson()){
            return response()->json($client);
        }
        
        return view('dashboard.client.show', [
            'client' => $client
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
        $client = Client::find($id);
        return view('dashboard.client.create', [
            'client' => $client
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
        $validated = $this->validateData($request, $id);
        
        DB::table('clients')
            ->where('id', $id)
            ->update($validated);
        
        return redirect()->back()->with('success', 'Data saccessfuly saved!');
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
        $client = Client::find($id);
        return view('dashboard.client.create_password', [
            'client' => $client
        ]);
    }
    
    /**
     * Update the specific client password.
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
    
    public function checkEmail(Request $request, $id = null){
        
        $validate_rules = [
            'email' => [
                'required', 'email', ['max', 255], Rule::unique('clients')->where(function($query) use ($id) {
                    $query->where('is_deleted', '=', '0');
                    if(!is_null($id))
                        $query->where('id', '!=', $id);
                })
            ]
        ];
        
        $validator = Validator::make($request->all(), $validate_rules);
        if($validator->fails())
            return response()->json(false);
        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
    
    protected function mainValidationRules(){
        return [
            //Main rules
            'first_name' => 'required|max:255',
            'last_name' => 'max:255',
            'gender' => 'required|in:male,female',
            'birthdate' => 'nullable|regex:/\d{4}-\d{2}-\d{2}/i',
            'country' => 'max:255',
            'town' => 'max:255',
            'street' => 'max:255',
            
            //Suspension rules
            'status' => 'required|in:disable,complete,period',
            'suspend_from' => 'required_if:status,==,period|nullable|string|max:20|regex:/\d{2}-\d{2}-\d{4}/i',
            'suspend_to' => 'required_if:status,==,period|nullable|string|max:20|regex:/\d{2}-\d{2}-\d{4}/i',
        ];
    }
    
    
    // protected function validateData($request, $id = null){
    //     $rules = [
    //         // 'email' => [
    //         //     'required', 'email', ['max', 255], Rule::unique('clients')->where(function($query) {
    //         //         $query->where('is_deleted', '=', '0');
    //         //     })
    //         // ],
    //         'first_name' => 'required|max:255',
    //         'last_name' => 'max:255',
    //         'gender' => 'nullable|in:male,female',
    //         'phone' => 'nullable|regex:/^\+[0-9]{3,20}$/i',
    //         'birthdate' => 'nullable|regex:/\d{4}-\d{2}-\d{2}/i',
    //         'country' => 'max:255',
    //         'town' => 'max:255',
    //         'street' => 'max:255'
    //     ];
    // 
    //     if(is_null($id)){
    //         $rules['password'] = 'required|max:255';
    //         $rules['password_confirm'] = 'required|same:password';
    //         $rules['email'] = [
    //             'required', 'email', ['max', 255], Rule::unique('clients')->where(function($query) {
    //                 $query->where('is_deleted', '=', '0');
    //             })
    //         ];
    //     }else{
    //         $rules['email'] = [
    //             'required', 'email', ['max', 255], Rule::unique('clients')->where(function($query) use ($id) {
    //                 $query->where('is_deleted', '=', '0')->where('id', '!=', $id);
    //             })
    //         ];
    //     }
    // 
    //     $validated = $request->validate($rules);
    // 
    //     if(is_null($id)){
    //         $validated['password'] = Hash::make($validated['password']);
    //         unset($validated['password_confirm']);
    //     }
    // 
    //     return $validated;
    // }
    
    // protected function parseToCorrectDBDate($date_string){
    //     if(empty($date_string) || count($date_string_arr = explode('-', $date_string)))
    //         return $date_string;
    //     return $date_string_arr[2] . '-' . $date_string_arr[1] . '-' . $date_string_arr[0];
    // }
}
