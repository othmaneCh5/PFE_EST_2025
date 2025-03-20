<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(){
        return view("content.dashboard.dashboard-test");
    }
    public function index1(){
        return view("auth.login");
    }
}
