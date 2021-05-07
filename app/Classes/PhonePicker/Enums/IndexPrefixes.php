<?php
namespace App\Classes\PhonePicker\Enums;

use App\Classes\Traits\Enumerable;
// use App\Classes\PhonePicker\Enums\IndexPrefixes;

class IndexPrefixes
{
    use Enumerable;
    
    const VALUE = "phone_value_";
    const ID = "phone_id_";
    const TYPE = "phone_type_";
    const CUSTOM_TYPE = "phone_custom_type_";
    
}