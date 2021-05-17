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
    
    function customTitles(Request $request){
        $setting = \Setting::of(Keys::CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES);
        $data = $setting->getOrPlaceholder();
        dump($data);
        
        return view('dashboard.settings.clients_booking_calendar.custom_titles', [
            // 'languages' => \Setting::getOrPlaceholder(Keys::BOOKING_CALENDAR_LANGUAGES),
            // 'languages' => \Setting::getOrPlaceholder(Keys::BOOKING_CALENDAR_LANGUAGES),
        ]);
    }
    
    function languages(Request $request){
        $setting = \Setting::of(Keys::CLIENTS_BOOKING_CALENDAR_LANGUAGES);
        
        if($request->isMethod('post')){
            $validated = $request->validate(\Language::getValidationRules());
            
            $setting->parseAndSet($request->lang);
            
            return back()->with([
                'success' => 'Data successfuly saved!'
            ]);
        }
        
        return view('dashboard.settings.clients_booking_calendar.languages', [
            'languages' => $setting->getOrPlaceholder(),
        ]);
    }
}
