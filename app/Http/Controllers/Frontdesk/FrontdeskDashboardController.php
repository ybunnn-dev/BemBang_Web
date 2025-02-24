<?php

namespace App\Http\Controllers\Frontdesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontdeskDashboardController extends Controller
{
    public function index()
    {
        return view('frontdesk.dashboard'); 
    }
}
