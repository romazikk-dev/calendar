<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.client.index');
    }
    
    /**
     * Return list of clients for ajax request.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataList()
    {        
        $сlients = Client::select([
            DB::raw("clients.`id` as `id`"),
            DB::raw("CONCAT(clients.`first_name`,' ',clients.`last_name`) as `full_name`"),
            DB::raw("clients.`email`"),
            DB::raw("clients.`created_at` as `created_at`"),
        ])->where("clients.is_deleted", 0);
        
        return Datatables::eloquent($сlients)->filterColumn('full_name', function($query, $keyword) {
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
        return view('dashboard.client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        
        $validated['birthdate'] = $this->parseToCorrectDBDate($validated['birthdate']);
        $validated['user_id'] = auth()->user()->id;
        
        Client::create($validated);
        
        return redirect()->route('dashboard.client.index');
    }
    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
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
    
    protected function validateData($request, $id = null){
        $rules = [
            // 'email' => [
            //     'required', 'email', ['max', 255], Rule::unique('clients')->where(function($query) {
            //         $query->where('is_deleted', '=', '0');
            //     })
            // ],
            'first_name' => 'required|max:255',
            'last_name' => 'max:255',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|regex:/^\+[0-9]{3,20}$/i',
            'birthdate' => 'nullable|regex:/\d{4}-\d{2}-\d{2}/i',
            'country' => 'max:255',
            'town' => 'max:255',
            'street' => 'max:255'
        ];
        
        if(is_null($id)){
            $rules['password'] = 'required|max:255';
            $rules['password_confirm'] = 'required|same:password';
            $rules['email'] = [
                'required', 'email', ['max', 255], Rule::unique('clients')->where(function($query) {
                    $query->where('is_deleted', '=', '0');
                })
            ];
        }else{
            $rules['email'] = [
                'required', 'email', ['max', 255], Rule::unique('clients')->where(function($query) use ($id) {
                    $query->where('is_deleted', '=', '0')->where('id', '!=', $id);
                })
            ];
        }
        
        $validated = $request->validate($rules);
        
        if(is_null($id)){
            $validated['password'] = Hash::make($validated['password']);
            unset($validated['password_confirm']);
        }
        
        return $validated;
    }
    
    protected function parseToCorrectDBDate($date_string){
        if(empty($date_string) || count($date_string_arr = explode('-', $date_string)))
            return $date_string;
        return $date_string_arr[2] . '-' . $date_string_arr[1] . '-' . $date_string_arr[0];
    }
}
