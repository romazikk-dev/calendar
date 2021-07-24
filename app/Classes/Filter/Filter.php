<?php

namespace App\Classes\Filter;

use App\Models\Hall;
use App\Models\Worker;
use App\Models\Template;
use App\Models\Client;
use App\Classes\Filter\Enums\Items;
use App\Classes\Getter\Enums\Keys as GetterKeys;
use App\Classes\Getter\Hall\Enums\Params as HallGetterParams;
use App\Classes\Getter\Worker\Enums\Params as WorkerGetterParams;
use App\Classes\Getter\Template\Enums\Params as TemplateGetterParams;
use App\Classes\Getter\Client\Enums\Params as ClientGetterParams;

class Filter extends MainFilter{
    
    public function getFromCookie(){
        $filters = !empty($_COOKIE[$this->cookie_name]) ? json_decode($_COOKIE[$this->cookie_name], true) : null;
        if(empty($filters))
            return null;
            
        $parsed_filters = [];
        
        foreach([
            ['item' => Items::HALL, 'getter_key' => GetterKeys::HALLS, 'getter_param' => HallGetterParams::ID],
            ['item' => Items::WORKER, 'getter_key' => GetterKeys::WORKERS, 'getter_param' => WorkerGetterParams::ID],
            ['item' => Items::TEMPLATE, 'getter_key' => GetterKeys::TEMPLATES, 'getter_param' => TemplateGetterParams::ID],
            ['item' => Items::CLIENT, 'getter_key' => GetterKeys::CLIENTS, 'getter_param' => ClientGetterParams::ID],
        ] as $v)
            if($this->isFilter($filters, $v['item']))
                $parsed_filters[$v['item']] = \Getter::of($v['getter_key'])->get([
                    $v['getter_param'] => $filters[$v['item']]
                ]);
        
        if($this->isFilter($filters, Items::DURATION))
            $parsed_filters['duration'] = $filters[Items::DURATION];
        
        // dd($parsed_filters);
        
        return $parsed_filters;
    }
    
}