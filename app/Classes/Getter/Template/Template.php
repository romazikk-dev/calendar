<?php

namespace App\Classes\Getter\Template;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;

// use App\Classes\Getter\Template\Template;
use App\Models\Template as ModelTemplate;
use App\Classes\Getter\Template\Enums\Params;
use App\Models\User;
use App\Scopes\UserScope;

class Template{
    
    function get(array $params = []){
        if(!empty($params[Params::OWNER_ID]) && is_numeric($params[Params::OWNER_ID])){
            $template_model = ModelTemplate::withoutGlobalScope(UserScope::class)
                ->byUser($params[Params::OWNER_ID]);
        }else{
            $template_model = ModelTemplate::query();
        }
        
        if(!empty($params[Params::ID]) && is_numeric($params[Params::ID]))
            $template_model->where('id', (int)$params[Params::ID]);
        
        if(!empty($params[Params::WORKER_ID]) || !empty($params[Params::HALL_ID])){
            $template_model->whereHas('workers', function($query) use ($params) {
                if(!empty($params[Params::WORKER_ID])){
                    $query->byId($params[Params::WORKER_ID]);
                }
                if(!empty($params[Params::HALL_ID])){
                    $query->whereHas('halls', function($query) use ($params) {
                        $query->byId($params[Params::HALL_ID]);
                    });
                }
            });
        }
        
        // var_dump($template_model->toSql());
        // die();
        
        if(!empty($params[Params::WITH]))
            $template_model->with($params[Params::WITH]);
        
        $templates = $template_model->get();
        
        return $templates->isEmpty() || $templates === false ? null : $templates;
    }
    
}