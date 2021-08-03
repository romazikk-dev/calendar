<?php
namespace App\Classes\CalendarAlias;

use Illuminate\Support\Facades\Auth;
// use App\Classes\DurationRange\Enums\GetParams;

class Alias extends MainAlias{
    
    public function my(){
        dd('alias');
    }

    public function getAliasRegexp(){
        return $this->alias_regexp;
    }
    
    public function getByUser($user_id = null){
        if(empty($user_id = $this->getAuthUserIdIfEmpty($user_id, true)))
            return null;
        return $this->getByUserId($user_id);
    }
    
    public function setByUser($alias, $user_id = null){
        if(!$this->isCorrectAlias($alias) || empty($user_id = $this->getAuthUserIdIfEmpty($user_id, true)))
            return false;
        return $this->setByUserId($alias, $user_id);
    }
    
    public function getByAlias($alias, $column = null){
        return $this->getByAliasName($alias, $column);
    }

}