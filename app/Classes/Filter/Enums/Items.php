<?php
namespace App\Classes\Filter\Enums;

use App\Classes\Traits\Enumerable;

class Items{
    use Enumerable;
    
    const STATUS = "status";
    const HALL = "hall";
    const WORKER = "worker";
    const TEMPLATE = "template";
    const CLIENT = "client";
    const DURATION = "duration";
}