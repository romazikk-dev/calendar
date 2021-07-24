<?php

namespace App\Classes\Getter\Hall;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;

// use App\Classes\Getter\Template\Template;
use App\Models\Hall as ModelHall;
// use App\Models\Template as ModelTemplate;
use App\Classes\Getter\Hall\Enums\Params;
use App\Models\User;
use App\Scopes\UserScope;

class Hall extends MainHall{
    
    function get(array $params = []){
        if($this->isParam($params, Params::OWNER_ID)){
            $model = ModelHall::withoutGlobalScope(UserScope::class);
            if(is_array($params[Params::OWNER_ID])){
                $model->byUsers($params[Params::OWNER_ID]);
            }else{
                $model->byUser($params[Params::OWNER_ID]);
            }
        }else{
            $model = ModelHall::query();
        }
        
        if($this->isParam($params, Params::ID)){
            if(is_array($params[Params::ID])){
                $model->byIds($params[Params::ID]);
            }else{
                $model->byId($params[Params::ID]);
            }
        }
        
        if($this->isParam($params, Params::WORKER_ID) || $this->isParam($params, Params::TEMPLATE_ID)){
            $param_worker_id = $this->isParam($params, Params::WORKER_ID) ? $params[Params::WORKER_ID] : null;
            $param_template_id = $this->isParam($params, Params::TEMPLATE_ID) ? $params[Params::TEMPLATE_ID] : null;
            $model->whereHas('workers', function($query) use ($param_worker_id, $param_template_id) {
                if(!empty($param_worker_id)){
                    if(is_array($param_worker_id)){
                        $query->byIds($param_worker_id);
                    }else{
                        $query->byId($param_worker_id);
                    }
                }
                if(!empty($param_template_id))
                    $query->whereHas('templates', function($query) use ($param_template_id) {
                        if(is_array($param_template_id)){
                            $query->byIds($param_template_id);
                        }else{
                            $query->byId($param_template_id);
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
        
        $res = $model->get();
        return $res->isEmpty() || $res === false ? null : $res;
    }
    
}