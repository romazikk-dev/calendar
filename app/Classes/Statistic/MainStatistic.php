<?php
namespace App\Classes\Statistic;

use App\Models\Worker;
use App\Models\Hall;
use App\Models\Template;
use App\Models\Client;

class MainStatistic{
    
    protected $workers = null;
    protected $halls = null;
    protected $templlates = null;
    protected $clients = null;
    
    function __construct(){
        $this->workers = Worker::all();
        $this->halls = Hall::all();
        $this->templlates = Template::all();
        $this->clients = Client::all();
    }
    
    public function getWorkers(){
        return $this->workers;
    }
    
    public function getHalls(){
        return $this->halls;
    }
    
    public function getTemplates(){
        return $this->templlates;
    }
    
    public function getClients(){
        return $this->clients;
    }
    
}