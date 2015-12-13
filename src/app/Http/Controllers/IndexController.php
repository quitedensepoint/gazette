<?php

namespace Playnet\WwiiOnline\Gazette\Http\Controllers;

use Illuminate\Http\Request;

use Playnet\WwiiOnline\Gazette\Http\Requests;
use Playnet\WwiiOnline\Gazette\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * Displays the front page view
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Displays the allied version of the newspaper
     * 
     * @return \Illuminate\Http\Response
     */
    public function allied()
    {
        return view('allied');
    }
    
    /**
     * Displays the axis version of the newspaper
     * 
     * @return \Illuminate\Http\Response
     */    
    public function axis()
    {
        return view('axis');
    }      
    
}
