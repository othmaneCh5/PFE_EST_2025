<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index(){
        return view("content.dashboard.dashboard-test");
    }
}
