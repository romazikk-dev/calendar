<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\TemplateSpecifics;

class SpecificNotInUse implements Rule
{
    protected $attribute = null;
    
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
        // $this->attribute = $attribute;
        $specific = TemplateSpecifics::find($value);
        return $specific->templates->isEmpty();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Specific is in use.';
    }
}
