<?php
namespace App\Classes\Statistic\Statistics;

use App\Classes\Setting\Enums\Keys as SettingKeys;

class MainPageStatistic{
    
    protected $statistic = null;
    protected $carbon_now = null;
    
    function __construct($statistic){
        $this->statistic = $statistic;
        $this->carbon_now = \Carbon\Carbon::now(\Timezone::getCurrentTimezone());
    }
    
    function get(){
        return [
            'worker' => $this->getWorkerStatistic(),
            'halls' => $this->getHallStatistic(),
            'templates' => $this->getTemplateStatistic(),
            'clients' => $this->getClientStatistic(),
        ];
    }
    
    function getClientStatistic(){
        $clients = $this->statistic->getClients();
        $statistic = [
            'count' => $clients->count(),
        ];
        
        return $statistic;
    }
    
    function getTemplateStatistic(){
        $templates = $this->statistic->getTemplates();
        $statistic = [
            'count' => $templates->count(),
        ];
        
        return $statistic;
    }
    
    function getHallStatistic(){
        $halls = $this->statistic->getHalls();
        $statistic = [
            'count' => $halls->count(),
            'suspended' => 0
        ];
        
        foreach ($halls as $k => $v) {
            //Count suspended
            if(\Suspension::isSuspendedOnModel($v->suspension)) 
                $statistic['suspended']++;
        }
        
        return $statistic;
    }
    
    function getWorkerStatistic(){
        $workers = $this->statistic->getWorkers();
        $statistic = [
            'count' => $workers->count(),
            'suspended' => 0
        ];
        
        foreach ($workers as $k => $v) {
            //Count suspended
            if(\Suspension::isSuspendedOnModel($v->suspension)) 
                $statistic['suspended']++;
        }
        
        return $statistic;
    }
    
}