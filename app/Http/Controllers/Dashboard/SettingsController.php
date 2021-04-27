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
    public function bussinessHours(Request $request)
    {
        // $bussiness_hours = 
        // \Setting::sayHello();
        // dd(11111111);
        // dd(\Setting::getOrPlaceholder(Keys::DEFAULT_BUSSINESS_HOURS));
        // dd(11111111);
        
        if($request->isMethod('post'))
            dd($request->all());
        
        $business_hours = \Setting::getOrPlaceholder(Keys::DEFAULT_BUSSINESS_HOURS);
        // return view('dashboard.hall.create', [
        //     'business_hours' => $business_hours,
        // ]);
        
        // dd($business_hours);
        
        return view('dashboard.settings.hall.default_bussiness_hours', [
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
