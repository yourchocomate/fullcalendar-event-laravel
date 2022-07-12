<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Hash;
use Carbon\Date;

class MainController extends Controller
{
    public function index() 
    {
        return view('index');
    }
}
