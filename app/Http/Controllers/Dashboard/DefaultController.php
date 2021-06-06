<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    //
    function index(){
        // $statistic = \Statistic::getMainPageStatistic();
        // dd($statistic);
        return view('dashboard.default', [
            'statistic' => \Statistic::getMainPageStatistic()
        ]);
    }
}
