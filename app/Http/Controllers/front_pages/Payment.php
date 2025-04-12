<?php

namespace App\Http\Controllers\front_pages;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

class Payment extends Controller
{
  public function index(Request $request)
  {
    // Retrieve the "commande" query parameter from the URL.
    $commandeId = $request->query('commande');
    if (!$commandeId) {
      // Optionally redirect or throw an error if commande id is missing.
      abort(404, 'Order not found.');
    }
    // Retrieve the commande along with its client (and optionally products) using Eloquent.
    $commande = Commande::with('client', 'products')->findOrFail($commandeId);
    
    $pageConfigs = ['myLayout' => 'front'];
    return view('content.front-pages.payment-page', compact('pageConfigs', 'commande'));
  }
}
