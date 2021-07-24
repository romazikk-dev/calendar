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

class Template extends MainTemplate{
    
    function get(array $params = []){
        if($this->isParam($params, Params::OWNER_ID)){
            $model = ModelTemplate::withoutGlobalScope(UserScope::class);
            if(is_array($params[Params::OWNER_ID])){
                $model->byUsers($params[Params::OWNER_ID]);
            }else{
                $model->byUser($params[Params::OWNER_ID]);
            }
        }else{
            $model = ModelTemplate::query();
        }
        
        if($this->isParam($params, Params::ID)){
            if(is_array($params[Params::ID])){
                $model->byIds($params[Params::ID]);
            }else{
                $model->byId($params[Params::ID]);
            }
        }
        
        if($this->isParam($params, Params::WORKER_ID) || $this->isParam($params, Params::HALL_ID)){
            $param_worker_id = $this->isParam($params, Params::WORKER_ID) ? $params[Params::WORKER_ID] : null;
            $param_hall_id = $this->isParam($params, Params::HALL_ID) ? $params[Params::HALL_ID] : null;
            $model->whereHas('workers', function($query) use ($param_worker_id, $param_hall_id) {
                if(!empty($param_worker_id)){
                    if(is_array($param_worker_id)){
                        $query->byIds($param_worker_id);
                    }else{
                        $query->byId($param_worker_id);
                    }
                }
                if(!empty($param_hall_id))
                    $query->whereHas('halls', function($query) use ($param_hall_id) {
                        if(is_array($param_hall_id)){
                            $query->byIds($param_hall_id);
                        }else{
                            $query->byId($param_hall_id);
                        }
                    });
            });
        }
        
        if($this->isParam($params, Params::WITH)){
            if(is_array($params[Params::WITH])){
                foreach($params['with'] as $k => $v)
                    $model->with($v);
            }else{
                $model->with($params[Params::WITH]);
            }
        }
        
        $templates = $model->get();
        return $templates->isEmpty() || $templates === false ? null : $templates;
    }
    
}