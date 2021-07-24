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

class Client extends MainClient{
    
    function get(array $params = []){
        if($this->isParam($params, Params::OWNER_ID)){
            $model = ModelClient::withoutGlobalScope(UserScope::class);
            if(is_array($params[Params::OWNER_ID])){
                $model->byUsers($params[Params::OWNER_ID]);
            }else{
                $model->byUser($params[Params::OWNER_ID]);
            }
        }else{
            $model = ModelClient::query();
        }
    
        if($this->isParam($params, Params::ID)){
            if(is_array($params[Params::ID])){
                $model->byIds($params[Params::ID]);
            }else{
                $model->byId($params[Params::ID]);
            }
        }
        
        if($this->isParam($params, Params::WITH)){
            if(is_array($params[Params::WITH])){
                foreach($params['with'] as $k => $v)
                    $model->with($v);
            }else{
                $model->with($params[Params::WITH]);
            }
        }
    
        $clients = $model->get();
        return $clients->isEmpty() || $clients === false ? null : $clients;
    }
    
}