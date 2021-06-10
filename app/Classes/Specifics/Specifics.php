<?php

namespace App\Classes\Specifics;

// use App\Classes\Setting\Enums\Keys;
use App\Models\Setting as Setting;
use App\Models\TemplateSpecifics;

class Specifics extends MainSpecifics{
    
    protected $max_deep = 5;
    
    public function getMaxDeep(){
        return $this->max_deep;
    }
    
    public function getDbSpecificsAsKeyId($as_key_id = false){
        // $this->setDbSpecifics();
        // dd(1111);
        // dd($this->db_specifics);
        if(is_null($this->db_specifics))
            return null;
        return $as_key_id === true ? $this->db_specifics_as_key_id : $this->db_specifics;
    }
    
    public function getAllForVueInSettings(){
        $db_specifics = TemplateSpecifics::all();
        if($db_specifics->isEmpty())
            return [];
        
        // dd($db_specifics);
        
        return \Specifics::parseCollectionDbReesultToTreeArray($db_specifics);
    }
    
    public function createSpecificTitledTraceFromIdsTrace(int $specific_id, $specific_ids_trace, $template, $with_template_title = false){
        if(!empty($specific_ids_trace)){
            $specific_ids_trace_arr = explode(',', $specific_ids_trace);
            $specific_ids_trace_arr = array_map(function($val){
                return (int) trim($val);
            }, $specific_ids_trace_arr);
        }else{
            $specific_ids_trace_arr = [];
        }
        
        $specific_ids_trace_arr[] = $specific_id;
        
        $out = [];
        foreach($specific_ids_trace_arr as $k => $v){
            $out[] = $this->db_specifics_as_key_id[$v]['title'];
        }
        
        if($with_template_title === true)
            $out[] = $template['title'];
            
        return $out;
    }
    
    public function getPickedSpecificFromModel($model){
        if(!empty($model->specific)){
            $picked_specific = $model->specific->toArray();
            if(!empty($picked_specific['ids_trace'])){
                $ids_trace = explode(',', $picked_specific['ids_trace']);
                $picked_specific['ids_trace'] = array_map(function($v){
                    return (int) $v;
                }, $ids_trace);
                $picked_specific['ids_trace'][] = $picked_specific['id'];
            }else{
                $picked_specific['ids_trace'] = [$picked_specific['id']];
            }
            return $picked_specific;
        }
        return null;
    }
    
    public function removeAllByParentId(int $id){
        $idsToRemove = [$id];
        $getChildsById = function($id, $getChildsById) use (&$idsToRemove){
            $child_specifics = TemplateSpecifics::where('parent_id', $id)->get()->toArray();
            if(!empty($child_specifics)){
                foreach($child_specifics as $k => $v){
                    $idsToRemove[] = $v['id'];
                    $getChildsById($v['id'], $getChildsById);
                }
            }
        };
        
        $getChildsById($id, $getChildsById);
        
        TemplateSpecifics::whereIn('id', $idsToRemove)->delete();
    
        // var_dump(implode(',', $idsToRemove));
        // die();
        // $parent_specific = TemplateSpecifics::where('id', $id)->first();
        // 
        // // $getChildsById
        // $child_specifics = TemplateSpecifics::where('parent_id', $validated['id'])->get()->toArray();
        // if(!empty($child_specifics)){
        //     foreach($child_specifics as $k => $v){
        //         $idsToRemove[] = $v['id'];
        //         if()
        //     }
        // }
    }
    
    public function parseCollectionDbReesultToTreeArray($collection, $with_id_as_key = false){
        $i = 0;
        $parsed_fields = [];
        $max_deep = $this->max_deep;
        $in_use = false;
        
        $createChild = function($id, $createChild, &$deep) use (&$collection, &$max_deep, &$with_id_as_key, &$in_use){
            // $deep++;
            // if($deep > $max_deep)
            //     return;
            
            $children = [];
            foreach($collection as $item)
                if($item->parent_id === $id){
                    $deep++;
                    // dump($deep);
                    if($deep > $max_deep)
                        return;
                        
                    // if($in_use === false)
                    //     $in_use = $item->templates->isEmpty() ? false : true;
                    $in_use = $item->templates->isEmpty() ? false : true;
                        
                    $itm = [
                        'id' => $item->id,
                        // 'deep' => $deep,
                        'parent_id' => $item->parent_id,
                        'title' => $item->title,
                        'description' => $item->description,
                        'in_use' => $in_use,
                        'fields' => $createChild($item->id, $createChild, $deep),
                    ];
                    if($with_id_as_key === true){
                        $children[$item->id] = $itm;
                    }else{
                        $children[] = $itm;
                    }
                    
                    $deep--;
                    // dump($deep);
                }
            
            return $children;
        };
        
        foreach($collection as $item){
            $deep = 0;
            if(is_null($item->parent_id)){
                $in_use = $item->templates->isEmpty() ? false : true;
                $itm = [
                    'id' => $item->id,
                    // 'deep' => $deep,
                    'parent_id' => $item->parent_id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'in_use' => $in_use,
                    'fields' => $createChild($item->id, $createChild, $deep),
                ];
                if($with_id_as_key === true){
                    $parsed_fields[$item->id] = $itm;
                }else{
                    $parsed_fields[] = $itm;
                }
            }
            // dump($deep);
        }
        
        // dd($deep);
        
        return $parsed_fields;
    }
    
    public function parseDbReesultToTreeArray(array $fields, $with_id_as_key = false){
        $i = 0;
        $parsed_fields = [];
        $max_deep = $this->max_deep;
        
        $createChild = function($id, $createChild, &$deep) use (&$fields, &$max_deep, &$with_id_as_key){
            $deep++;
            if($deep > $max_deep)
                return;
            
            $children = [];
            foreach($fields as $k => &$v)
                if($v['parent_id'] === $id){
                    // $children[$v['key']] = [
                    $itm = [
                        'id' => $v['id'],
                        'parent_id' => $v['parent_id'],
                        'title' => $v['title'],
                        'description' => $v['description'],
                        'fields' => $createChild($v['id'], $createChild, $deep),
                    ];
                    if($with_id_as_key === true){
                        $children[$v['id']] = $itm;
                    }else{
                        $children[] = $itm;
                    }
                }
            
            return $children;
        };
        
        foreach($fields as $k => &$v){
            $deep = 0;
            if(is_null($v['parent_id'])){
                $itm = [
                // $parsed_fields[] = [
                    'id' => $v['id'],
                    'parent_id' => $v['parent_id'],
                    'title' => $v['title'],
                    'description' => $v['description'],
                    'fields' => $createChild($v['id'], $createChild, $deep),
                ];
                if($with_id_as_key === true){
                    $parsed_fields[$v['id']] = $itm;
                }else{
                    $parsed_fields[] = $itm;
                }
            }
        }
        
        return $parsed_fields;
    }
    
    public function parseRequestToArray($fields){
        $i = 0;
        $parsed_fields = [];
        $max_deep = $this->max_deep;
        
        $createChild = function($key, $createChild, &$deep) use (&$fields, &$max_deep){
            $deep++;
            if($deep > $max_deep)
                return;
            
            $children = [];
            foreach($fields as $k => &$v)
                if($v['parent_key'] === $key)
                    // $children[$v['key']] = [
                    $children[] = [
                        'title' => $v['title'],
                        'key' => $v['key'],
                        'description' => $v['description'],
                        'fields' => $createChild($v['key'], $createChild, $deep),
                    ];
            
            return $children;
        };
        
        foreach($fields as $k => &$v){
            $deep = 0;
            if(is_null($v['parent_key'])){
                // $parsed_fields[$v['key']] = [
                $parsed_fields[] = [
                    'title' => $v['title'],
                    'key' => $v['key'],
                    'description' => $v['description'],
                    'fields' => $createChild($v['key'], $createChild, $deep),
                ];
            }
        }
        
        return $parsed_fields;
    }
    
    public function isAllLevelsUniqueInRequest(){
        if(!request()->has('field'))
            return false;
        $fields = request()->get('field');
        if(!is_array($fields))
            return false;
        $fields = $this->parseRequestToArray($fields);
        
        $checkUniqueness = function($fields, $checkUniqueness) use (&$isUnique){
            if(!$isUnique)
                return;
                
            // $fields = array_values($fields);
            // dump(array_column($fields, 'key'));
            
            $arr_titles = array_column($fields, 'title');
            dump($fields);
            dd($arr_titles);
            // $unique_titles = array_unique($arr_titles);
            // $has_duplicate_titles = count($arr_titles) == count($unique_titles);
            // dump([$arr_titles, $unique_titles]);
            // $duplicate_titles = array_diff_assoc($arr_titles, $unique_titles);
            // dump($unique_titles);
            
            $arr_keys = array_column($fields, 'key');
            $unique_keys = array_unique($arr_keys);
            $has_duplicate_keys = count($arr_keys) == count($unique_keys);
            // $duplicate_keys = array_diff_assoc($arr_keys, $unique_keys);
            dump([$arr_keys, $unique_keys]);
            
            if($has_duplicate_titles === true || $has_duplicate_keys === true){
                $isUnique = false;
                return;
            }
            
            // dump($isUnique);
            
            foreach($fields as $k => $v){
                if(!$isUnique)
                    return;
                if(!empty($v['fields']))
                    $checkUniqueness($v['fields'], $checkUniqueness);
            }
        };
        
        $isUnique = true;
        $checkUniqueness($fields, $checkUniqueness);
        
        dump($fields);
        dd($isUnique);
        return $isUnique;
    }
    
    public function getRulesDependOnRequest($fields){
        dump($fields);
        // dd(111);
        $rules = [];
        $fields = $this->parseRequestToArray($fields);
        $prefixes = [];
        dump($fields);
        $deep = 0;
        $getLevelRule = function($fields, $getLevelRule) use (&$rules, &$deep, &$prefixes){
            foreach($fields as $k => $v){
                // if(is_null($prefix) || !is_string($prefix))
                //     $prefix = '';
                dump(implode($prefixes));
                $rules[implode($prefixes) . $k . '.title'] = 'required|string|unique';
                $rules[implode($prefixes) . $k . '.key'] = 'required|string|unique';
                
                if(!empty($v['fields'])){
                    $deep++;
                    $prefixes[] =  $k . '.fields.';
                    // $prefix .= $k . '.fields.';
                    $getLevelRule($v['fields'], $getLevelRule);
                }
            }
            
            $deep--;
            array_pop($prefixes);
        };
        
        // $fields = $this->parseRequestToArray($fields);
        
        $getLevelRule($fields, $getLevelRule);
        
        // foreach($fields as $k => $v){
        //     $rule = 
        // }
        dd($rules);
    }
    
    // function getBookingCalendarAvailableLanguages(){
    //     return $this->booking_calendar_available_languages;
    // }
    // 
    // function getValidationRules(){
    //     return $this->validation_rules;
    // }
    
}