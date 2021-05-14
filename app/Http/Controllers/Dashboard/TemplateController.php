<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Template;
use App\Models\Worker;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.template.index');
    }
    
    /**
     * Return list of templates for ajax request(from datatable js).
     *
     * @return \Illuminate\Http\Response
     */
    public function dataList(){
        $templates = Template::select([
            DB::raw("templates.`id`"),
            DB::raw("templates.`title`"),
            DB::raw("templates.`price`"),
            DB::raw("templates.`duration`"),
            DB::raw("templates.`created_at`"),
            DB::raw("(SELECT COUNT(*) FROM template_worker WHERE template_worker.`template_id` = templates.`id`) as `workers_count`")
        ])->with('workers')->where('is_deleted', 0);
        
        return Datatables::eloquent($templates)
            ->editColumn('duration', function(Template $template) {
                return date('H:i', $template->duration);
            })
            // ->filterColumn('duration', function($query, $keyword) {
            //     $sql = "DATE_FORMAT(templates.`duration`, '%H:%i') like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })
            ->orderColumn('workers_count', function ($query, $order) {
                $query->orderBy('workers_count', $order);
            })
            ->toJson(true);
            
        // return Datatables::eloquent($workers)->filterColumn('full_name', function($query, $keyword) {
        //             $sql = "CONCAT(first_name,' ',last_name)  like ?";
        //             $query->whereRaw($sql, ["%{$keyword}%"]);
        //         })
        //         ->orderColumn('status', function ($query, $order) {
        //             $query->orderBy('sort_status', $order);
        //             $query->orderBy('sort_status_2', $order);
        //         })
        //         ->orderColumn('halls_count', function ($query, $order) {
        //             $query->orderBy('halls_count', $order);
        //         })
        //         ->toJson(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('dashboard.template.create', [
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
        
        // dd($request->all());
        
        $validated = $request->validate([
            // Main validation rules
            'title' => 'required|max:255',
            'duration' => 'required|string|max:10|regex:/\d{2}:\d{2}/i',
            'short_description' => 'max:255',
            'price' => 'nullable|regex:/^([0-9]){1,6}(\.[0-9]{2})?$/',
            'description' => 'max:1000',
            'notice' => 'max:1000',
            
            // Assign worker validation rules
            'assign_workers' => 'nullable|array',
        ]);
        
        $validated['duration'] = strtotime('1970-01-01 ' . $validated['duration'] .':00');
        
        if(!empty($validated['assign_workers'])){
            $assign_workers = array_keys($validated['assign_workers']);
            $workers = Worker::whereIn('id', $assign_workers)->get();
            if(count($workers) != count($assign_workers))
                return back()->withErrors(['assign_worker_count', 'You trying assign to hall not existing workers']);
        }
        
        $validated['user_id'] = auth()->user()->id;
        
        if(($template = Template::create($validated)) && (!empty($assign_workers)))
            foreach($assign_workers as $worker_id)
                $template->workers()->attach($worker_id);
        
        return redirect()->route('dashboard.template.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id){
        $model = Template::where("id", $id);
        
        if($request->has('with_workers'))
            $model->with('workers');
        
        $template = $model->first();
        
        if($request->wantsJson()){                
            // $template->makeVisible(['business_hours']);
            return response()->json($template);
        }
        return view('dashboard.hall.show', [
            'template' => $template
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
        $template = Template::find($id);
        
        $assign_workers = [];
        // dd($assign_worker);
        if(old('assign_workers')){
            $assign_workers = old('assign_workers');
        }else{
            foreach($template->workers as $itm){
                $assign_workers[$itm->id] = 'on';
            }
        }
        
        return view('dashboard.template.create', [
            'template' => $template,
            'assign_workers' => $assign_workers,
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
        $validated = $request->validate([
            // Main validation rules
            'title' => 'required|max:255',
            'duration' => 'required|string|max:10|regex:/\d{2}:\d{2}/i',
            'price' => 'nullable|regex:/^([0-9]){1,6}(\.[0-9]{2})?$/',
            'description' => 'max:1000',
            'short_description' => 'max:255',
            'notice' => 'max:1000',
            
            // Assign worker validation rules
            'assign_workers' => 'nullable|array',
        ]);
        
        // dd($validated['duration']);
        $validated['duration'] = strtotime('1970-01-01 ' . $validated['duration'] .':00');
        // dd($validated['duration']);
        
        if(!empty($validated['assign_workers'])){
            $assign_workers = array_keys($validated['assign_workers']);
            unset($validated['assign_workers']);
            $workers = Worker::whereIn('id', $assign_workers)->get();
            if(count($workers) != count($assign_workers))
                return back()->withErrors(['assign_workers_count', 'You trying assign not existing workers']);
        }
        
        $res = DB::table('templates')
            ->where('id', $id)
            ->update($validated);
            
        $template = Template::find($id);
        $template->workers()->detach();
        
        if(!empty($assign_workers))
            foreach($assign_workers as $worker_id)
                $template->workers()->attach($worker_id);
        
        return redirect()->back()->with('success', 'Data saccessfuly saved!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Template::where('id', $id)
            ->where('is_deleted', 0)
            ->update(['is_deleted' => 1]);
            
        return redirect()->route('dashboard.template.index');
    }
}
