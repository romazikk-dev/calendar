<?php

namespace App\Classes\Getter\Worker;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;

// use App\Classes\Getter\Template\Template;
// use App\Models\Template as ModelTemplate;
use App\Models\Worker as ModelWorker;
use App\Classes\Getter\Worker\Enums\Params;
// use App\Models\User;
use App\Scopes\UserScope;

class Worker extends MainWorker{
    
    function get(array $params = []){
        if($this->isParam($params, Params::OWNER_ID)){
            $model = ModelWorker::withoutGlobalScope(UserScope::class);
            if(is_array($params[Params::OWNER_ID])){
                $model->byUsers($params[Params::OWNER_ID]);
            }else{
                $model->byUser($params[Params::OWNER_ID]);
            }
        }else{
            $model = ModelWorker::query();
        }
        
        if($this->isParam($params, Params::ID)){
            if(is_array($params[Params::ID])){
                $model->byIds($params[Params::ID]);
            }else{
                $model->byId($params[Params::ID]);
            }
        }
        
        if($this->isParam($params, Params::TEMPLATE_ID)){
        // if(!empty($params[Params::TEMPLATE_ID])){
            $model->whereHas('templates', function($query) use ($params) {
                if(is_array($params[Params::TEMPLATE_ID])){
                    $query->byIds($params[Params::TEMPLATE_ID]);
                }else{
                    $query->byId($params[Params::TEMPLATE_ID]);
                }
            });
        }
        
        if($this->isParam($params, Params::HALL_ID)){
        // if(!empty($params[Params::HALL_ID])){
            $model->whereHas('halls', function($query) use ($params) {
                if(is_array($params[Params::HALL_ID])){
                    $query->byIds($params[Params::HALL_ID]);
                }else{
                    $query->byId($params[Params::HALL_ID]);
                }
            });
        }
        
        // var_dump($template_model->toSql());
        // die();
        
        if($this->isParam($params, Params::WITH)){
            if(is_array($params[Params::WITH])){
                foreach($params['with'] as $k => $v)
                    $model->with($v);
            }else{
                $model->with($params[Params::WITH]);
            }
        }
        
        $result = $model->get();
        if($result->isEmpty() || $result === false)
            return null;
        
        return !empty($params[Params::ONLY_FIRST_ITEM]) ? $result[0] : $result;
    }
    
}