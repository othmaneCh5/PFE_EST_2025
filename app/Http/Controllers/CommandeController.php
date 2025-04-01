<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index(){
        $clients = Client::select('id', 'name', 'email')->get();
        return view('content.apps.app-ecommerce-order-list', compact('clients'));
    }

    /**
     * Returns JSON data for DataTables.
     */
    public function getData()
    {
        // Eager load the associated client to retrieve its id, name, and email.
        $commandes = Commande::with('client')->get();

        // Map each commande to a format matching your DataTables columns.
        $mapped = $commandes->map(function ($commande) {
            return [
                'id'           => $commande->id,
                'id_client'    => $commande->id_client,
                'client_name'  => $commande->client ? $commande->client->name : null,
                'client_email' => $commande->client ? $commande->client->email : null,
                'paiement'     => $commande->paiement,
                'status'       => $commande->status,
                'methode'      => $commande->methode,
                'created_at'   => $commande->created_at->toDateTimeString(),
                'updated_at'   => $commande->updated_at->toDateTimeString()
            ];
        });

        return response()->json(['data' => $mapped]);
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request.
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'paiement'  => 'required|string|max:255',
            'status'    => 'required|string|max:255',
            'methode'   => 'required|string|max:255',
        ]);

        // Create the new order.
        $commande = Commande::create([
            'id_client' => $request->client_id,
            'paiement'  => $request->paiement,
            'status'    => $request->status,
            'methode'   => $request->methode,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Commande added',
            'data'    => $commande,
        ]);
    }

    /**
     * Retrieve the specified order for editing.
     */
    public function edit($id)
    {
        // Eager-load or just fetch the commande by id
        $commande = Commande::with('client')->findOrFail($id);
        // Return the relevant fields in JSON
        return response()->json([
            'id'        => $commande->id,
            'id_client' => $commande->id_client,
            'paiement'  => $commande->paiement,
            'status'    => $commande->status,
            'methode'   => $commande->methode
            // include client_name, email if you want (or fetch from $commande->client)
        ]);
    }
    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request.
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'paiement'  => 'required|string|max:255',
            'status'    => 'required|string|max:255',
            'methode'   => 'required|string|max:255',
        ]);

        // Retrieve and update the order.
        $commande = Commande::findOrFail($id);
        $commande->update([
            'id_client' => $request->client_id,
            'paiement'  => $request->paiement,
            'status'    => $request->status,
            'methode'   => $request->methode,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Commande updated',
        ]);
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy($id)
    {
        $commande = Commande::findOrFail($id);
        $commande->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commande deleted',
        ]);
    }

    public function show($id)
    {
        // Eager load the related client + products
        $commande = Commande::with(['client', 'products'])->findOrFail($id);
  
        return view('content.apps.app-ecommerce-commande-details', compact('commande'));
    }

    public function addProductView($commandeId)
    {
        $commande = Commande::findOrFail($commandeId);
        // fetch all products
        $products = Product::orderBy('id')->get();
        return view('content.apps.app-ecommerce-add-product', compact('commande','products'));
    }

    public function attachProduct(Request $request, $commandeId)
    {
        $commande = Commande::findOrFail($commandeId);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qte'        => 'required|integer|min:1',
        ]);

        // Attach or update pivot entry
        $commande->products()->syncWithoutDetaching([
            $request->product_id => [
                'qte' => $request->qte
            ]
        ]);

        return redirect()->route('orders.show', $commandeId)
                        ->with('success', 'Produit ajouté à la commande');
    }

    public function detachProduct($commandeId, $productId)
    {
        $commande = Commande::findOrFail($commandeId);
        $commande->products()->detach($productId);

        return redirect()->route('orders.show', $commandeId)
                        ->with('success', 'Produit supprimé de la commande');
    }

}
