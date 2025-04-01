<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    public function index()
  {
    $this->authorize('view categories');
    $categories = Category::withCount('products')->get();
    return view('content.apps.app-ecommerce-category-list' , compact('categories'));
  }


  public function add(Request $request)
    {
        $this->authorize('add categories');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'string|max:255',
            'parent_id' => 'nullable|exists:categories,id', // Category is optional
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image is optional
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle the image upload (if provided)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public'); // Store the image in the 'public/categories' directory
        }

        // Create the product
        $category = Category::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'parent_id' => $request->input('parent_id'), // This can be null
            'image' => $imagePath, // This can be null
        ]);

        // Redirect with a success message
        return redirect()->route('product-categories')->with('success', 'Category added successfully!');
    }

    public function update(Request $request, $id)
{
    $this->authorize('edit categories');
    // Validate the request
    $request->validate([
        'name' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:categories,id',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Find the category
    $category = Category::findOrFail($id);

    // Update the category
    $category->name = $request->name;
    $category->parent_id = $request->parent_id;
    $category->description = $request->description;

    // Handle image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('categories', 'public');
        $category->image = $imagePath;
    }

    $category->save();

    return redirect()->route('product-categories')->with('success', 'Category modified successfully!');
}

public function delete(Request $request)
{
    $this->authorize('delete categories');
    $id = $request->query('id');
    $category = Category::find($id); 
    if ($category) {
        $category->delete();
        return redirect()->route('product-categories')->with('success', 'category updated successfully!');
    }
}
}
