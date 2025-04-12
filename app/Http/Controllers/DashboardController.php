<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\FournisseurOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
{
    use AuthorizesRequests;
    public function index(){
        $sales = Sale::with(['product', 'commande'])->get();
        $numberOfSales = $sales->count();
        $clients = Client::all();
        $numberOfClients = $clients->count();
        $revenue = $sales->sum('price'); // assuming 'total_price' is a column in your sales table
        $averageSales = intval($sales->avg('price')/7);
        $products = Product::all();
        $numberOfProducts = $products->count();
        $popularProducts = Product::take(5)->get();
        // Return the view with all values

        $user = auth()->user();

        if ($user->hasRole('fournisseur')) {
            $this->authorize('access commandes_fournisseurs');
    
            // Retrieve the logged-in fournisseur
            $fournisseurId = Auth::user()->fournisseur->id; // Assuming each user has a related fournisseur
        
            // Get the orders where the fournisseur_id matches the logged-in fournisseur's ID
            $orders = FournisseurOrder::where('fournisseur_id', $fournisseurId)->get();
        
            // Pass the orders to the view
            return view('content.apps.commandes-fournisseurs', compact('orders'));
        }
        return view("content.dashboard.dashboard-test", compact('sales', 'numberOfSales', 'numberOfClients', 'revenue', 'averageSales', 'numberOfProducts' , 'popularProducts'));
    }
    
}
