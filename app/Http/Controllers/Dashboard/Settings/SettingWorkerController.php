<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Setting\Enums\Keys;

class SettingWorkerController extends Controller
{
    /**
     * List of all settings of controller.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('dashboard.settings.worker.index', [
            'nav' => \Setting::getNav('worker')
        ]);
    }
    
    /**
     * Handle default business hours setting.
     *
     * @return \Illuminate\Http\Response
     */
    public function businessHours(Request $request){
        $setting = \Setting::of(Keys::WORKER_DEFAULT_BUSINESS_HOURS);
        
        if($request->isMethod('post')){
            $business_hour_rule = 'nullable|string|max:10|regex:/\d{2}:\d{2}/i';
            $validated = $request->validate([
                'business_hours' => 'required|array',
                'business_hours.*.start_hour' => $business_hour_rule,
                'business_hours.*.end_hour' => $business_hour_rule,
                'business_hours.*.is_weekend' => 'in:on',
            ]);
            
            $setting->parseAndSet($request->business_hours);
            
            return back()->with([
                'success' => 'Data successfuly saved!'
            ]);
        }
        
        return view('dashboard.settings.worker.default_business_hours', [
            'business_hours' => $setting->getOrPlaceholder(),
        ]);
    }
    
}
