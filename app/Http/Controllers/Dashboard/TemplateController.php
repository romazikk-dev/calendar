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
    public function dataList()
    {        
        $templates = Template::select([
            DB::raw("templates.`id`"),
            DB::raw("templates.`title`"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(`duration`), '%H:%i') as duration"),
            DB::raw("templates.`created_at`"),
            // DB::raw("(SELECT COUNT(*) FROM hall_worker WHERE hall_worker.`hall_id` = halls.`id`) as `workers_count`")
        ])
        ->where('is_deleted', 0);
        
        return Datatables::eloquent($templates)->toJson(true);
        // return Datatables::eloquent($workers)->filterColumn('full_name', function($query, $keyword) {
        //             $sql = "CONCAT(first_name,' ',last_name)  like ?";
        //             $query->whereRaw($sql, ["%{$keyword}%"]);
        //         })->toJson(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.template.create');
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
            'title' => 'required|max:255',
            'price' => 'nullable|regex:/^([0-9]){1,6}(\.[0-9]{2})?$/',
            'description' => 'max:1000',
            'short_description' => 'max:255',
            'notice' => 'max:1000',
            'duration' => 'required|string|max:10|regex:/\d{2}:\d{2}/i',
            'assign_worker' => 'nullable|array',
        ]);
        
        // $duration_arr = explode(':', $validated['duration']);
        // 
        // $duration = '1970-01-01 ' . $validated['duration'] .':00';
        // dd(
        //     \Carbon\Carbon::parse('1970-01-01 ' . $validated['duration'] .':00')->format('s'),
        //     $duration,
        //     strtotime($duration)
        // );
        
        $validated['duration'] = strtotime('1970-01-01 ' . $validated['duration'] .':00');
        
        if(!empty($validated['assign_worker'])){
            $assign_workers = array_keys($validated['assign_worker']);
            unset($validated['assign_worker']);
            
            $workers = Worker::whereIn('id', $assign_workers)->get();
            
            if(count($workers) != count($assign_workers))
                return back()->withErrors(['assign_workers_count', 'You trying assign not existing workers']);
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
    public function show($id)
    {
        $template = Template::find($id);
        return view('dashboard.template.show', [
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
        
        // $business_hours_raw = json_decode($template->business_hours);
        // $business_hours = [];
        // foreach($business_hours_raw as $itm){
        //     $business_hours[$itm->weekday]['start_hour'] = $itm->start;
        //     $business_hours[$itm->weekday]['end_hour'] = $itm->end;
        //     if(is_bool($itm->is_weekend) && $itm->is_weekend)
        //         $business_hours[$itm->weekday]['is_weekend'] = 'on';
        // }
        
        $assign_workers = [];
        // dd($assign_worker);
        foreach($template->workers as $itm){
            $assign_workers[$itm->id] = 'on';
            // dump($itm->id);
        }
        
        // dd(1111);
        
        return view('dashboard.template.create', [
            'template' => $template,
            // 'business_hours' => $business_hours,
            'assign_workers' => $assign_workers,
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
        $validated = $request->validate([
            'title' => 'required|max:255',
            'price' => 'nullable|regex:/^([0-9]){1,6}(\.[0-9]{2})?$/',
            'description' => 'max:1000',
            'short_description' => 'max:255',
            'notice' => 'max:1000',
            'duration' => 'required|string|max:10|regex:/\d{2}:\d{2}/i',
            'assign_worker' => 'nullable|array',
        ]);
        
        $validated['duration'] = strtotime('1970-01-01 ' . $validated['duration'] .':00');
        
        if(!empty($validated['assign_worker'])){
            $assign_workers = array_keys($validated['assign_worker']);
            unset($validated['assign_worker']);
            
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
