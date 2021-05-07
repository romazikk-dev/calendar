<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Setting\Enums\Keys;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.settings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function businessHours(Request $request)
    {
        
        if($request->isMethod('post')){
            $business_hour_rule = 'nullable|string|max:10|regex:/\d{2}:\d{2}/i';
            $business_weekend_rule = 'in:on';
            
            $validated = $request->validate([
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
            
            \Setting::parseAndSet(Keys::DEFAULT_BUSINESS_HOURS, $request->business_hours);
            return back()->with([
                'success' => 'Data successfuly saved!'
            ]);
        }
        
        return view('dashboard.settings.hall.default_business_hours', [
            'business_hours' => \Setting::getOrPlaceholder(Keys::DEFAULT_BUSINESS_HOURS),
        ]);
    }
    
    /**
     * Worker default bussiness hours.
     *
     * @return \Illuminate\Http\Response
     */
    public function workerDefaultBusinessHours(Request $request)
    {
        // dd(111);
        if($request->isMethod('post')){
            $business_hour_rule = 'nullable|string|max:10|regex:/\d{2}:\d{2}/i';
            $business_weekend_rule = 'in:on';
            
            $validated = $request->validate([
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
            
            // dd($request->business_hours);
            
            \Setting::parseAndSet(Keys::WORKER_DEFAULT_BUSINESS_HOURS, $request->business_hours);
            return back()->with([
                'success' => 'Data successfuly saved!'
            ]);
        }
        
        // dd(Keys::WORKER_DEFAULT_BUSINESS_HOURS);
        // dd(\Setting::getOrPlaceholder(Keys::WORKER_DEFAULT_BUSINESS_HOURS));
        
        return view('dashboard.settings.worker.default_business_hours', [
            'business_hours' => \Setting::getOrPlaceholder(Keys::WORKER_DEFAULT_BUSINESS_HOURS),
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
}
