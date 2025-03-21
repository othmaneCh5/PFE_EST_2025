<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Illuminate\Support\Facades\Log; // Import Log facade

class ProductController extends Controller
{
    // Open the products list
    public function index()
    {
        $products = Product::with('category')->get();
        return view("content.apps.app-ecommerce-product-list" , compact('products'));
    }

    // Open the add product page
    public function open_add_product()
    {
        return view("content.apps.app-ecommerce-product-add");
    }

    // Add a product
    public function add(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'productTitle' => 'required|string|max:255',
            'description' => 'string|max:255',
            'productSku' => 'required|string|unique:products,sku',
            'productBarcode' => 'required|string|unique:products,barcode',
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

        // Handle the image upload (if provided)
        $imagePath = null;
        if ($request->hasFile('file')) {
            $imagePath = $request->file('file')->store('products', 'public'); // Store the image in the 'public/products' directory
        }

        // Create the product
        $product = Product::create([
            'name' => $request->input('productTitle'),
            'description' => $request->input('description'),
            'sku' => $request->input('productSku'),
            'barcode' => $request->input('productBarcode'),
            'price' => $request->input('productPrice'),
            'category_id' => $request->input('category'), // This can be null
            'status' => $request->input('status'),
            'image' => $imagePath, // This can be null
        ]);

        // Redirect with a success message
        return redirect()->route('product-list')->with('success', 'Product added successfully!');
    }
 
}
