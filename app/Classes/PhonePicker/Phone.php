<?php

namespace App\Classes\PhonePicker;

use App\Models\Worker;
use App\Classes\PhonePicker\Enums\Types as Types;
use App\Classes\PhonePicker\Enums\IndexPrefixes;
use Illuminate\Http\Request;

class Phone extends MainPhone{
    
    public function saveAllPhones(Request $request, Worker $worker, $delete_old = true){
        if($delete_old)
            $worker->phones()->delete();
        for($i = 0; $i < 10; $i++){
            $phone_value = $request->get(IndexPrefixes::VALUE . $i);
            if(!empty($phone_value)){
                $phone_data = [
                    'phone' => $phone_value
                ];
                if($request->has(IndexPrefixes::TYPE . $i)){
                    $phone_type = $request->get(IndexPrefixes::TYPE . $i);
                    $phone_custom_type = $request->has(IndexPrefixes::CUSTOM_TYPE . $i) ?
                        $request->get(IndexPrefixes::CUSTOM_TYPE . $i) : null;
                    $phone_data['type'] = $phone_type == Types::CUSTOM && !empty($phone_custom_type) ?
                        $phone_custom_type : $phone_type;
                }
                $worker->phones()->create($phone_data);
            }
        }
    }
    
    public function getIndexPrefixesForVue(){
        return [
            'value' => IndexPrefixes::VALUE,
            'id' => IndexPrefixes::ID,
            'type' => IndexPrefixes::TYPE,
            'custom_type' => IndexPrefixes::CUSTOM_TYPE,
        ];
    }
    
    public function getPhonesValidetionRulesAndCustomMessages($request){
        $messages = [];
        $rules = [];
        $phone_types = implode(',', Types::all());
        
        for($i = 0; $i <= 10; $i++){
            $phone_value = $request->get(IndexPrefixes::VALUE . $i);
            if(!empty($phone_value)){
                $rules[IndexPrefixes::VALUE . $i] = 'nullable|max:30';
                $rules[IndexPrefixes::ID . $i] = 'nullable|integer';
                $rules[IndexPrefixes::TYPE . $i] = 'required_with:phone_value_' . $i . '|in:' . $phone_types . '';
                $rules[IndexPrefixes::CUSTOM_TYPE . $i] = 'required_if:phone_type_' . $i . ',==,custom|max:30';
                if(!array_key_exists(IndexPrefixes::VALUE . $i . ".max", $messages))
                    $messages[IndexPrefixes::VALUE . $i . ".max"] = 'The phone must not be greater than :max characters.';
                if(!array_key_exists(IndexPrefixes::CUSTOM_TYPE . $i . ".required_if", $messages))
                    $messages[IndexPrefixes::CUSTOM_TYPE . $i . ".required_if"] = 'Field is required when type is custom.';
            }
        }
        
        return [
            'rules' => $rules,
            'messages' => $messages,
        ];
    }
    
    public function getAllForVue($worker = null){
        
        $error_messages = null;
        $errors = session()->get('errors');
        if(!empty($errors))
            $error_messages = $errors->default->messages();
        
        $parsed_phones = [];
        
        if(old('_token') !== null){
            
            for($i = 0; $i < 10; $i++){
                // if(!empty(old(IndexPrefixes::VALUE . $i)))
                //     dump(old(IndexPrefixes::VALUE . $i));
                // dump(IndexPrefixes::VALUE);
                if(!empty(old(IndexPrefixes::VALUE . $i))){
                    $parsed_phone = [
                        'phone' => [
                            'value' => old(IndexPrefixes::VALUE . $i),
                            'error' => !empty($error_messages[IndexPrefixes::VALUE . $i][0]) ?
                                $error_messages[IndexPrefixes::VALUE . $i][0] : null,
                        ],
                        'id' => [
                            'value' => old(IndexPrefixes::ID . $i),
                            'error' => !empty($error_messages[IndexPrefixes::ID . $i][0]) ?
                                $error_messages[IndexPrefixes::ID . $i][0] : null,
                        ],
                        'type' => [
                            'value' => old(IndexPrefixes::TYPE . $i),
                            'error' => !empty($error_messages[IndexPrefixes::TYPE . $i][0]) ?
                                $error_messages[IndexPrefixes::TYPE . $i][0] : null,
                        ],
                        'custom_type' => [
                            'value' => old(IndexPrefixes::CUSTOM_TYPE . $i),
                            'error' => !empty($error_messages[IndexPrefixes::CUSTOM_TYPE . $i][0]) ?
                                $error_messages[IndexPrefixes::CUSTOM_TYPE . $i][0] : null,
                        ],
                    ];
                    $parsed_phones[] = $parsed_phone;
                }
            }
            
        }else if(old('_token') === null && !is_null($worker) && $worker instanceof Worker){
            
            foreach($worker->phones as $phone){
                $parsed_phone = [
                    'phone' => [
                        'value' => $phone->phone,
                        'error' => null,
                    ],
                    'id' => [
                        'value' => $phone->id,
                        'error' => null,
                    ],
                ];
                if(!in_array($phone->type, Types::all())){
                    $parsed_phone['type'] = [
                        'value' => 'custom',
                        'error' => null,
                    ];
                    $parsed_phone['custom_type'] = [
                        'value' => $phone->type,
                        'error' => null,
                    ];
                }else{
                    $parsed_phone['type'] = [
                        'value' => $phone->type,
                        'error' => null,
                    ];
                    $parsed_phone['custom_type'] = [
                        'value' => null,
                        'error' => null,
                    ];
                }
                $parsed_phones[] = $parsed_phone;
            }
            
        }
        
        return $parsed_phones;
    }
    
}