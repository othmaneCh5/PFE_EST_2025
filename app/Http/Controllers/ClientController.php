<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class ClientController extends Controller
{
    /**
     * Shows the Blade page that has the DataTable & form.
     */
    public function index()
    {
        return view('content.apps.app-ecommerce-customer-all');
    }

    /**
     * Returns JSON data for DataTables
     */
    public function getData()
    {
        // 1) Fetch from DB
        $clients = Client::all();

        // 2) Transform or rename fields to match your DataTables columns
        $mapped = $clients->map(function ($client) {
            return [
                'id'        => $client->id,
                'id_client' => $client->id, // or $client->id_client if that’s a column
                'name'      => $client->name,
                'email'     => $client->email,
                'tel'       => $client->tel,
                'adresse'   => $client->adresse,
                'image'     => '' // Or any actual 'image' field
            ];
        });

        // DataTables expects {"data": [...]} at top level
        return response()->json(['data' => $mapped]);
    }

    /**
     * Create/store a new client.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customerName'  => 'required|string|max:255',
            'customerEmail' => 'required|email|unique:clients,email',
        ]);

        $formattedAddress = trim("{$request->customerAddress1}, {$request->pin}, {$request->customerTown}", ' ,');

        $client = Client::create([
            'name'    => $request->customerName,
            'email'   => $request->customerEmail,
            'tel'     => $request->customerContact ?? '',
            'adresse' => $formattedAddress,
            // Just placeholders if your DB migration requires them
            'num_carte' => '',
            'exp_date'  => now(),
            'cvv_code'  => ''
        ]);

        return response()->json(['success' => true, 'message' => 'Client added', 'data' => $client]);
    }

    /**
     * Fetch a client for editing (Ajax).
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return response()->json($client);
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customerName'  => 'required|string|max:255',
            'customerEmail' => 'required|email|unique:clients,email,' . $id,
        ]);

        $client = Client::findOrFail($id);

        $formattedAddress = trim("{$request->customerAddress1}, {$request->pin}, {$request->customerTown}", ' ,');

        $client->update([
            'name'    => $request->customerName,
            'email'   => $request->customerEmail,
            'tel'     => $request->customerContact ?? '',
            'adresse' => $formattedAddress
        ]);

        return response()->json(['success' => true, 'message' => 'Client updated']);
    }

    /**
     * Delete a client from storage.
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json(['success' => true, 'message' => 'Client deleted']);
    }

    public function updateCard(Request $request, $id)
{
    $request->validate([
        'num_carte' => 'required|string',
        'name' => 'required|string|max:255',
        'exp_date' => 'required|date_format:m/y',
        'cvv_code' => 'required|string|max:3'
    ]);

    $client = Client::findOrFail($id);

    // Convert MM/YY to a proper date
    $expDate = \Carbon\Carbon::createFromFormat('m/y', $request->exp_date)->endOfMonth();

    $client->update([
        'num_carte' => $request->num_carte,
        'name' => $request->name,
        'exp_date' => $expDate,
        'cvv_code' => $request->cvv_code,
    ]);

    return redirect()->back()->with('success', 'Card updated successfully.');
}

}
