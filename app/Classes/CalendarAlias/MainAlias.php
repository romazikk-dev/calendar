<?php
namespace App\Classes\CalendarAlias;

use App\Models\CalendarAlias as AliasModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
// use App\Classes\CalendarAlias\Enums\Types;

class MainAlias{
    
    protected $alias_regexp = '/^[A-z0-9_-]+$/';
    
    protected function getByAliasName(string $alias, $column = null){
        if(empty($model = AliasModel::where('alias', $alias)->first()))
            return null;
        if(!empty($column) && !empty($model->{$column}))
            return $model->{$column};
        return $model;
    }
    
    protected function getByUserId(int $user_id, $as_model = false){
        $model = AliasModel::where('user_id', $user_id)
            ->where('worker_id', 0)
            ->first();
            
        if(empty($model))
            return null;
        return $as_model ? $model : $model->alias;
    }
    
    protected function setByUserId(string $alias, int $user_id){
        if(empty($this->getByUserId($user_id))){
            $model = AliasModel::create([
                'alias' => $alias,
                'user_id' => $user_id,
            ]);
        }else{
            AliasModel::where([
                ['user_id', $user_id], ['worker_id', 0]
            ])->update(['alias' => $alias]);
            $model = $this->getByUserId($user_id, true);
            // dd($model);
        }
        
        if(!empty($model))
            return $model->alias;
        return false;
    }
    
    protected function getAuthUserIdIfEmpty($user_id, $check_user_id = false){
        if(!empty($user_id)){
            if($check_user_id === true && empty(User::find($user_id)))
                return null;
            return $user_id;
        }
        if(!Auth::check())
            return null;
        return auth()->user()->id;
    }
    
    protected function isCorrectAlias($alias){
        return preg_match($this->alias_regexp . "i", $alias);
    }

}