<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\TemplateSpecifics;

class SpecificsMaxDeep implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $maxDeep = \Specifics::getMaxDeep();
        $deep = 1;
        $passes = true;
        $hasParent = function(int $parent_id, callable $hasParent) use (&$deep, $maxDeep, &$passes){
            // if(empty($parent_id))
            //     return;
            if(!$passes)
                return;
            if($deep >= $maxDeep){
                $passes = false;
                return;
            }
                
            $specific = TemplateSpecifics::where(['id' => $parent_id])->first();
            // var_dump($spsecific);
            if(!empty($specific)){
                if(is_null($specific->parent_id))
                    return;
                // var_dump($specific->parent_id);
                $deep++;
                $hasParent($specific->parent_id, $hasParent);
            }else{
                $passes = false;
                return;
            }
        };
        
        $hasParent($value, $hasParent);
        
        return $passes;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $maxDeep = \Specifics::getMaxDeep();
        return 'You can not create specifics deeper then '.$maxDeep.' level.';
    }
}
