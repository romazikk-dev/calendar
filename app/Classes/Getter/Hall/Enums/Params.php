<?php
namespace App\Classes\Getter\Hall\Enums;

use App\Classes\Traits\Enumerable;

// use App\Classes\Getter\Hall\Enums\Params;

class Params{
    
    use Enumerable;
    
    const ID = "id";
    const WORKER_ID = "worker_id";
    const TEMPLATE_ID = "template_id";
    const OWNER_ID = "owner_id";
    const WITH = "with";
}