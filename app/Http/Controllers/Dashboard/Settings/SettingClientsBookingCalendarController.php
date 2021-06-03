<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Setting\Enums\Keys;

class SettingClientsBookingCalendarController extends Controller
{
    function index(){
        return view('dashboard.settings.clients_booking_calendar.index', [
            'nav' => \Setting::getNav('clients_booking_calendar')
        ]);
    }
    
    function main(Request $request){
        $setting = \Setting::of(Keys::CLIENTS_BOOKING_CALENDAR_MAIN);
        // dd($setting->getOrPlaceholder());
        // dd('main');
        
        if($request->isMethod('post')){
            $validated = $request->validate([
                "max_future_booking_offset" => "required|integer|max:1000",
            ]);
            
            // dd($validated);
            
            $setting->parseAndSet($validated);
            
            return back()->with([
                'success' => 'Data successfuly saved!'
            ]);
        }
        
        return view('dashboard.settings.clients_booking_calendar.main', [
            'setting' => $setting->getOrPlaceholder(),
        ]);
    }
    
    function customTitles(Request $request){
        $setting = \Setting::of(Keys::CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES);
        // $titles = $setting->getOrPlaceholder();
        // dump($data);
        
        // dd($setting->getPlaceholderKeys());
        
        if($request->isMethod('post')){
            $placeholder_keys = $setting->getPlaceholderKeys();
            // dd($placeholder_keys);
            if(empty($placeholder_keys))
                return back()->with([
                    'error' => 'You can not save data, no placeholder exist!'
                ]);
                
            $validated = $request->validate([
                "field" => "array",
                "field.*" => "array",
                "field.*.*" => "nullable|string",
                "field_name" => "nullable|string|in:" . implode(',', $placeholder_keys),
            ]);
            
            // dd($validated);
            
            $setting->parseAndSet($validated);
            
            // dd($validated);
            // 
            return back()->with([
                'success' => 'Data successfuly saved!'
            ]);
        }
        
        // dump($setting->getPlaceholder());
        // dd($setting->getOrPlaceholder());
        
        return view('dashboard.settings.clients_booking_calendar.custom_titles', [
            'titles' => $setting->getOrPlaceholder(),
        ]);
    }
    
    function languages(Request $request){
        $setting = \Setting::of(Keys::CLIENTS_BOOKING_CALENDAR_LANGUAGES);
        
        if($request->isMethod('post')){
            $validated = $request->validate(\Language::getValidationRules());
            
            // $setting->parseAndSet($request->lang);
            $setting->parseAndSet($validated);
            
            return back()->with([
                'success' => 'Data successfuly saved!'
            ]);
        }
        
        // dump($setting->getOrPlaceholder());
        // dd($setting->getPlaceholder());
        
        return view('dashboard.settings.clients_booking_calendar.languages', [
            'languages' => $setting->getOrPlaceholder(),
        ]);
    }
}
