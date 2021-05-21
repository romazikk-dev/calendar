<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingTemplateController extends Controller
{
    
    /**
     * List of all settings of controller.
     *
     * @return \Illuminate\Http\Response
     */
    function index(){
        return view('dashboard.settings.template.index', [
            'nav' => \Setting::getNav('template')
        ]);
    }
    
    /**
     * Sets cpecifics.
     *
     * @return \Illuminate\Http\Response
     */
    function specifics(){
        
        
        return view('dashboard.settings.template.specifics', [
            // 'nav' => \Setting::getNav('template')
            'validation_messages' => \Lang::get('validation'),
        ]);
    }
    
}
