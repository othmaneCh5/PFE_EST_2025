<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use App\Models\FournisseurOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FournisseurController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('view fournisseurs');
        $fournisseurs = Fournisseur::all();
        return view('content.apps.app-fournisseurs-list', compact('fournisseurs'));
    }
    public function add(Request $request)
    {
        $this->authorize('create fournisseurs');

       
        
        $fournisseur = new Fournisseur();
        $fournisseur->name = $request->input('name');
        $fournisseur->email = $request->input('email');
        $fournisseur->phone = $request->input('phone');
        $fournisseur->address = $request->input('address');
        
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')), // Don't forget to hash the password
        ]);
    
        // Assign the 'fournisseur' role to the newly created user
        $user->assignRole('fournisseur');
    
        // Associate the user with the fournisseur
        $fournisseur->user()->associate($user);
        $fournisseur->save();

        return redirect()->route('app-fournisseurs-list')->with('success', 'Fournisseur added successfully.');
    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit fournisseurs');
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
        $this->authorize('delete fournisseurs');
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

    if ($order->status === 'shiped') {
        $order->status = 'received';
        $order->save();

        return response()->json(['success' => true, 'status' => 'received']);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Order status must be "shiped" to confirm receipt.'
        ], 400); // 400 Bad Request
    }
}


public function cancel($id)
{
    
    $order = FournisseurOrder::findOrFail($id);
    $order->status = "canceled"; // canceled
    $order->save();

    return response()->json(['success' => true, 'status' => 'canceled']);
}

//fournisseur

public function index1()
{
    $this->authorize('access commandes_fournisseurs');
    
    // Retrieve the logged-in fournisseur
    $fournisseurId = Auth::user()->fournisseur->id; // Assuming each user has a related fournisseur

    // Get the orders where the fournisseur_id matches the logged-in fournisseur's ID
    $FournisseurOrders = FournisseurOrder::where('fournisseur_id', $fournisseurId)->get();

    // Pass the orders to the view
    return view('content.apps.app-fournisseurs-list', compact('FournisseurOrders'));
}



public function ship($id)
{
    $this->authorize('access commandes_fournisseurs');
    $order = FournisseurOrder::findOrFail($id);
    $order->status = "shiped"; // shipped
    $order->save();

    return response()->json(['success' => true, 'status' => 'shipped']);
}
public function reject($id)
{
    $this->authorize('access commandes_fournisseurs');
    $order = FournisseurOrder::findOrFail($id);
    $order->status = "rejected"; // rejected
    $order->save();

    return response()->json(['success' => true, 'status' => 'rejected']);
}

public function fixFournisseursWithoutUsers()
{
    $fournisseurs = Fournisseur::whereNull('user_id')->get();

    foreach ($fournisseurs as $fournisseur) {
        $user = User::create([
            'name' => $fournisseur->name,
            'email' => $fournisseur->email,
            'password' => Hash::make('password123'), // Temporary password
        ]);

        // Assign role
        $user->assignRole('fournisseur');

        // Link user to fournisseur
        $fournisseur->user_id = $user->id;
        $fournisseur->save();
    }

    return 'Fixed fournisseurs without users!';
}
}
