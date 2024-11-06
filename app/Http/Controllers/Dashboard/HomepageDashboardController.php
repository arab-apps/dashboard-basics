<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomepageDashboardController extends Controller
{
    //
    public function index(){
        // dd('hi');
        return view('admin.home');
    }
}
