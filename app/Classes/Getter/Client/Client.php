<?php

namespace App\Classes\Getter\Client;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;

// use App\Classes\Getter\Template\Template;
use App\Models\Client as ModelClient;
use App\Classes\Getter\Client\Enums\Params;
use App\Models\User;
use App\Scopes\UserScope;

class Client{
    
    function get(array $params = []){
        if(!empty($params[Params::OWNER_ID]) && is_numeric($params[Params::OWNER_ID])){
            $model = ModelClient::withoutGlobalScope(UserScope::class)
                ->byUser($params[Params::OWNER_ID]);
        }else{
            $model = ModelClient::query();
        }
        
        if(!empty($params[Params::ID]) && is_numeric($params[Params::ID]))
            $model->where('id', (int)$params[Params::ID]);
        
        if(!empty($params[Params::WITH]))
            $model->with($params[Params::WITH]);
        
        $templates = $model->get();
        
        return $templates->isEmpty() || $templates === false ? null : $templates;
    }
    
}