<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    //
    function index(){
        // dd(111);
        return view('dashboard.default');
    }
}
