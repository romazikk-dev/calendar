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
use App\Models\User;
use App\Scopes\UserScope;

class Worker{
    
    function get(array $params = []){
        if(!empty($params[Params::OWNER_ID]) && is_numeric($params[Params::OWNER_ID])){
            $model = ModelWorker::withoutGlobalScope(UserScope::class)
                ->byUser($params[Params::OWNER_ID]);
        }else{
            $model = ModelWorker::query();
        }
        
        if(!empty($params[Params::ID]) && is_numeric($params[Params::ID]))
            $model->where('id', (int)$params[Params::ID]);
        
        if(!empty($params[Params::TEMPLATE_ID])){
            $model->whereHas('templates', function($query) use ($params) {
                $query->byId($params[Params::TEMPLATE_ID]);
            });
        }
        
        if(!empty($params[Params::HALL_ID])){
            $model->whereHas('halls', function($query) use ($params) {
                $query->byId($params[Params::HALL_ID]);
            });
        }
        
        // var_dump($template_model->toSql());
        // die();
        
        if(!empty($params[Params::WITH]) && is_array($params[Params::WITH])){
            foreach($params[Params::WITH] as $k => $v){
                $model->with($v);
            }
        }
        
        $templates = $model->get();
        
        return $templates->isEmpty() || $templates === false ? null : $templates;
    }
    
}