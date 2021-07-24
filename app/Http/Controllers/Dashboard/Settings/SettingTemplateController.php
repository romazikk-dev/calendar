<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemplateSpecifics;
use App\Rules\SpecificsUniqueLevel;
use App\Rules\SpecificsMaxDeep;
use App\Rules\SpecificNotInUse;
use Illuminate\Support\Facades\Validator;
use App\Classes\TemporaryMessages\Enums\Keys as TemporaryMessagesKeys;
use App\Classes\Setting\Enums\Keys as SettingKeys;

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
    function specifics(Request $request){
        // dd(\TempMsgs::getByKey(TemporaryMessagesKeys::PLAN_TEMPLATE_SPECIFICS_CAREFUL, true));
        
        if($request->isMethod('post')){
            // Remove action
            if($request->has('action') && $request->action == 'remove'){
                $validator = Validator::make($request->all(), [
                    // 'id' => 'required|integer|exists:template_specifics,id',
                    'id' => ['required', 'integer', 'exists:template_specifics,id', new SpecificNotInUse()],
                ]);
                
                if($validator->fails())
                    return response()->json([
                        'status' => 'error',
                        'errors' => $validator->errors()
                    ]);
                    
                $validated = $validator->valid();
                \Specifics::removeAllByParentId($validated['id']);
                
                return response()->json([
                    'status' => 'success',
                    'specifics' => \Specifics::getAllForVueInSettings(),
                ]);
            }
            
            // Validate title uniqueness action
            if($request->has('action') && $request->action == 'validate_title_uniqueness'){
                $validator = Validator::make($request->all(), [
                    'modal_title_val' => [new SpecificsUniqueLevel()],
                    'parent_id' => 'nullable|integer|exists:template_specifics,id',
                    'id' => 'nullable|integer|exists:template_specifics,id',
                ]);
                
                if($validator->fails())
                    return response()->json(false);
                return response()->json(true);
            }
            
            // Validate request data
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string', 'max:255', new SpecificsUniqueLevel()],
                'description' => 'nullable|string|max:255',
                'parent_id' => ['nullable', 'integer', 'exists:template_specifics,id', new SpecificsMaxDeep(), new SpecificNotInUse()],
                'id' => 'nullable|integer|exists:template_specifics,id',
            ]);
            
            if($validator->fails())
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ]);
            
            $validated = $validator->valid();
            
            if(empty($validated['id'])){
                // Create new specific
                $specific = TemplateSpecifics::create($validated);
            }else{
                // Update specific
                TemplateSpecifics::where('id', $validated['id'])
                    ->update([
                        'title' => $validated['title'],
                        'description' => $validated['description'],
                    ]);
            }
            
            return response()->json([
                'status' => 'success',
                'specifics' => \Specifics::getAllForVueInSettings(),
            ]);
        }
        
        $temp_msg = \TempMsgs::getByKey(TemporaryMessagesKeys::PLAN_TEMPLATE_SPECIFICS_CAREFUL, true);
        
        // dd(\Specifics::getAllForVueInSettings());
        
        return view('dashboard.settings.template.specifics.show', [
            'validation_messages' => \Lang::get('validation'),
            'specifics' => \Specifics::getAllForVueInSettings(),
            'max_deep' => \Specifics::getMaxDeep(),
            'temp_msg' => empty($temp_msg) || $temp_msg['is_disabled'] === true ? null : $temp_msg,
            // 'temp_msg' => \TempMsgs::getByKey(TemporaryMessagesKeys::PLAN_TEMPLATE_SPECIFICS_CAREFUL, true),
        ]);
    }
    
    function main(Request $request){
        $setting = \Setting::of(SettingKeys::TEMPLATE_MAIN);
        // dd($setting->getOrPlaceholder());
        // dd('main');
        
        if($request->isMethod('post')){
            $validated = $request->validate([
                "duration_range_start" => "required|integer|min:0|max:720",
                "duration_range_end" => "required|integer|min:0|max:720",
            ]);
            
            // dd($validated);
            
            $setting->parseAndSet($validated);
            
            return back()->with([
                'success' => 'Data successfuly saved!'
            ]);
        }
        
        // dd($setting->getOrPlaceholder());
        
        return view('dashboard.settings.template.main', [
            'setting' => $setting->getOrPlaceholder(),
        ]);
    }
    
}
