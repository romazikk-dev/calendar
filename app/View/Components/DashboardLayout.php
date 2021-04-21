<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DashboardLayout extends Component
{
    // public $my = 111111;
    
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.dashboard');
        // return view('layouts.dashboard', [
        //     'my' => 111111
        // ]);
    }
}
