<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;
    // Open the products list
    public function index()
    {
        $this->authorize('view products');
        $categories = Category::all();
        $products = Product::with('category')->get();
        return view("content.apps.app-ecommerce-product-list" , compact('products' , 'categories'));
    }

    // Open the add product page
    public function open_add_product()
    {
        $this->authorize('create products');
        $categories = Category::all();
        $fournisseurs = Fournisseur::all();
        return view("content.apps.app-ecommerce-product-add" , compact('categories' , 'fournisseurs'));
    }

    // Add a product
    public function add(Request $request)
    {
        $this->authorize('create products');
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'productTitle' => 'required|string|max:255',
            'description' => 'string|max:255',
            'quantity' => 'required|numeric|min:0',
            'productBarcode' => 'required|string|unique:products,barcode',
            'productPrice' => 'required|numeric|min:0',
            'category' => 'nullable|exists:categories,id',
            'fournisseur' => 'nullable|exists:fournisseurs,id', // Category is optional
            'status' => 'required|in:Publié,Planifié,Inactif',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image is optional
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle the image upload (if provided)
        $imagePath = null;
        if ($request->hasFile('file')) {
            $imagePath = $request->file('file')->store('products', 'public'); // Store the image in the 'public/products' directory
        }

        // Create the product
        $product = Product::create([
            'name' => $request->input('productTitle'),
            'description' => $request->input('description'),
            'quantity' => $request->input('quantity'),
            'barcode' => $request->input('productBarcode'),
            'price' => $request->input('productPrice'),
            'category_id' => $request->input('category'),
            'fournisseur_id' => $request->input('fournisseur'), // This can be null
            'status' => $request->input('status'),
            'image' => $imagePath, // This can be null
        ]);

        // Redirect with a success message
        return redirect()->route('product-list')->with('success', 'Product added successfully!');
    }

    public function edit(Request $request, $id)
{
    $this->authorize('edit products');
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'productTitle' => 'required|string|max:255',
        'description' => 'string|max:255',
        'quantity' => 'required|numeric|min:0',
        'productBarcode' => 'required|string|unique:products,barcode,' . $id,
        'productPrice' => 'required|numeric|min:0',
        'category' => 'nullable|exists:categories,id', // Category is optional
        'status' => 'required|in:Publié,Planifié,Inactif',
        'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image is optional
    ]);

    // If validation fails, redirect back with errors
    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Find the product to update
    $product = Product::findOrFail($id);

    // Handle the image upload (if provided)
    $imagePath = $product->image; // Retain the existing image by default
    if ($request->hasFile('file')) {
        // Delete the old image if it exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        // Store the new image
        $imagePath = $request->file('file')->store('products', 'public');
    }

    // Update the product
    $product->update([
        'name' => $request->input('productTitle'),
        'description' => $request->input('description'),
        'quantity' => $request->input('quantity'),
        'barcode' => $request->input('productBarcode'),
        'price' => $request->input('productPrice'),
        'category_id' => $request->input('category'), // This can be null
        'status' => $request->input('status'),
        'image' => $imagePath, // This can be null
    ]);

    // Redirect with a success message
    return redirect()->route('product-list')->with('success', 'Product updated successfully!');
}

public function delete(Request $request)
{
    $this->authorize('delete products');
    $id = $request->query('id');
    $product = Product::find($id); 
    if ($product) {
        $product->delete();
        return redirect()->route('product-list')->with('success', 'Product updated successfully!');
    }
}
}
