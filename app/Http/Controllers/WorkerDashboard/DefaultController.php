<?php

namespace App\Http\Controllers\WorkerDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    //
    function index(){
        // dd(111);
        return view('worker_dashboard.default');
    }
}
