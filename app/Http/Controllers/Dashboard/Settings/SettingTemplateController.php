<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemplateSpecifics;
use App\Rules\SpecificsUniqueLevel;
use App\Rules\SpecificsMaxDeep;
use Illuminate\Support\Facades\Validator;

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
        if($request->isMethod('post')){
            
            // Remove action
            if($request->has('action') && $request->action == 'remove'){
                $validator = Validator::make($request->all(), [
                    'id' => 'required|integer|exists:template_specifics,id',
                ]);
                
                if($validator->fails())
                    return response()->json(false);
                    
                $validated = $validator->valid();
                
                \Specifics::removeAllByParentId($validated['id']);
                
                $db_specifics = TemplateSpecifics::all()->toArray();
                if(!empty($db_specifics))
                    $parsed_specifics = \Specifics::parseDbReesultToTreeArray($db_specifics);
                    
                return response()->json(
                    !empty($parsed_specifics) ? $parsed_specifics : []
                );
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
                'parent_id' => ['nullable', 'integer', 'exists:template_specifics,id', new SpecificsMaxDeep()],
                'id' => 'nullable|integer|exists:template_specifics,id',
            ]);
            
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ]);
            }
            
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
            
            $db_specifics = TemplateSpecifics::all()->toArray();
            if(!empty($db_specifics))
                $parsed_specifics = \Specifics::parseDbReesultToTreeArray($db_specifics);
            
            return response()->json(
                !empty($parsed_specifics) ? $parsed_specifics : []
            );
        }
        
        $db_specifics = TemplateSpecifics::all()->toArray();
        if(!empty($db_specifics))
            $parsed_specifics = \Specifics::parseDbReesultToTreeArray($db_specifics);
        
        return view('dashboard.settings.template.specifics.show', [
            'validation_messages' => \Lang::get('validation'),
            'specifics' => !empty($parsed_specifics) ? $parsed_specifics : [],
            'max_deep' => \Specifics::getMaxDeep(),
        ]);
    }
    
    /**
     * Sets cpecifics.
     *
     * @return \Illuminate\Http\Response
     */
    function __specifics(Request $request){
        
        if($request->isMethod('post')){
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                // 'field.*.key' => 'required|string|regex:/^[\w]+$/|max:255|distinct',
                'description' => 'nullable|string|max:255',
                'parent_id' => 'nullable|integer|exists:template_specifics,id',
                'id' => 'nullable|integer|exists:template_specifics,id',
            ]);
            
            // \Specifics::my();
            
            // $field = $request->field;
            // $keys = array_keys($field);
            // dd($request->all());
            
            dd('unique');
            
            if(\Specifics::isAllLevelsUniqueInRequest()){
                dd('unique');
            }else{
                dd('not unique');
            }
            
            $fields = \Specifics::getRulesDependOnRequest($request->field);
            
            // $fields = \Specifics::parseRequestToArray($request->field);
            dd($fields);
            
            // dump($keys);
            // dd($field);
            
            $validated = $request->validate([
                'field' => 'array',
                'field.*' => 'array',
                'field.*.title' => 'required|string|max:255',
                'field.*.key' => 'required|string|regex:/^[\w]+$/|max:255|distinct',
                'field.*.description' => 'nullable|string|max:255'
            ]);
            
            
            // Holiday::where([
            //     'holidayable_type' => null,
            //     'holidayable_id' => null
            // ])->delete();
            // 
            // $holidays = \Holiday::getAllFromRequest();
            // if(!empty($holidays))
            //     foreach($holidays as $holiday)
            //         Holiday::create($holiday);
            // 
            // return back()->with([
            //     'success' => 'Data successfuly saved!'
            // ]);
        }
        
        return view('dashboard.settings.template.specifics', [
            // 'nav' => \Setting::getNav('template')
            'validation_messages' => \Lang::get('validation'),
        ]);
    }
    
}
