<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\TemplateSpecifics;

class SpecificsUniqueLevel implements Rule
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
        $parent_id = request()->has('parent_id') ? request()->parent_id : null;
        $id = request()->has('id') ? request()->id : null;
        
        $specifics = TemplateSpecifics::where([
            'parent_id' => $parent_id
        ])->get()->toArray();
        
        if(empty($specifics))
            return true;
            
        // var_dump($specifics['title']);
        // var_dump($value);
        // die();
        
        // var_dump($specifics);
        // die();
        $value = strtolower($value);
        foreach($specifics as $specific){
            // var_dump($specific['title']);
            // var_dump($value);
            // die();
            if(strtolower($specific['title']) === $value){
                // var_dump(77777);
                // die();
                if(empty($id) || $specific['id'] != $id)
                    return false;
            }
        }
        // die();
        
        // var_dump($value);
        // die();
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // return 'The validation error message.';
        return \Lang::get('validation.unique');
        // return 'The validation error message.';
    }
}
