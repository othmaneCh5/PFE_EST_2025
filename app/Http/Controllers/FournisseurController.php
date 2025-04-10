<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use App\Models\FournisseurOrder;
use Illuminate\Support\Facades\Auth;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::all();
        return view('content.apps.app-fournisseurs-list', compact('fournisseurs'));
    }
    public function add(Request $request)
    {
        $fournisseur = new Fournisseur();
        $fournisseur->name = $request->input('name');
        $fournisseur->email = $request->input('email');
        $fournisseur->phone = $request->input('phone');
        $fournisseur->address = $request->input('address');
        $fournisseur->save();

        return redirect()->route('app-fournisseurs-list')->with('success', 'Fournisseur added successfully.');
    }

    public function update(Request $request, $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->name = $request->input('name');
        $fournisseur->email = $request->input('email');
        $fournisseur->phone = $request->input('phone');
        $fournisseur->address = $request->input('address');
        $fournisseur->save();

        return redirect()->route('app-fournisseurs-list')->with('success', 'Fournisseur updated successfully.');
    }
    public function delete(Request $request)
    {
        $id = $request->query('id');
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->delete();

        return redirect()->route('app-fournisseurs-list')->with('success', 'Fournisseur deleted successfully.');
    }
    public function open_commandes(){
        $orders = FournisseurOrder::all();
        $products = Product::all();
        $fournisseurs = Fournisseur::all();
        return view('content.apps.app-ecommerce-referrals', compact('orders' , 'products', 'fournisseurs'));
    }

    public function add_order(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    // Optional: You can fetch product price from DB
    $product = \App\Models\Product::find($request->product_id);

    // Create a new order
    FournisseurOrder::create([
        'fournisseur_id' => $request->fournisseur_id,
        'product_id' => $request->product_id,
        'ordered_by_user_id' => Auth::id(), // make sure user is authenticated
        'quantity' => $request->quantity,
        'price' => $product->price ?? 0, // fall back to 0 if no price
        'status' => 'pending', // or any default status
        'order_date' => now(),
        'notes' => '', // optional, if you want to add a textarea later
    ]);

    return redirect()->back()->with('success', 'Order added successfully.');
}

public function confirm($id)
{
    $order = FournisseurOrder::findOrFail($id);
    $order->status = "received"; // received
    $order->save();

    return response()->json(['success' => true, 'status' => 'received']);
}

public function cancel($id)
{
    $order = FournisseurOrder::findOrFail($id);
    $order->status = "canceled"; // canceled
    $order->save();

    return response()->json(['success' => true, 'status' => 'canceled']);
}
}
