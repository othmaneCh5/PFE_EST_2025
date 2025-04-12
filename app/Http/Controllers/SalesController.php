<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        // Fetch all sales data
        $sales = Sale::with(['product', 'commande'])->get();
        return view('content.apps.sales', compact('sales'));
    }
}
