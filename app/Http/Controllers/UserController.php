<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('view users');
        
        // Get all users except those with the "fournisseur" role
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'fournisseur');
        })->get();
        
        // Get all roles (if you need them in the view)
        $roles = Role::all();
        
        return view('content.apps.app-user-list', compact('users', 'roles'));
    }
    

  public function add(Request $request)
    {
        $this->authorize('create users');
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|max:255', 
            'password' => 'required|string|max:255',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'nullable',
            'phone_number' => 'required|string',
            'dob' => 'required|date|before:today',
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle the image upload (if provided)
        $imagePath = null;
        if ($request->hasFile('profile_photo_path')) {
            $imagePath = $request->file('profile_photo_path')->store('users', 'public'); // Store the image in the 'public/products' directory
        }

        // Create the product
        $user = User::create([
            'name' => $request->input('name'),
            'password' => $request->input('password'),
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
            'profile_photo_path' => $imagePath,
            'dob' => $request->input('dob'), 
        ]);
        $role = Role::find($request->input('role')); // Find the role by ID
        $user->assignRole($role); // Assign the role to the user

        // Redirect with a success message
        return redirect()->route('app-user-list')->with('success', 'User added successfully!');
    }

    public function update(Request $request, $id)
{
    $this->authorize('edit users');
   
    $user = User::findOrFail($id);

   
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Validate the request data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255', // Remove '$id' concatenation here
        'email' => 'required|max:255',
        'password' => 'nullable|string|min:8|confirmed',
        'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'status' => 'nullable',
        'phone_number' => 'required|string', // Remove '$id' concatenation here
        'dob' => 'required|date|before:today',
    ]);
    

    // If validation fails, redirect back with errors
    if ($validator->fails()) {
        // Debugging: Check validation errors
        dd($validator->errors());
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

   
    $imagePath = $user->profile_photo_path;
    if ($request->hasFile('profile_photo_path')) {
        // Delete old image if it exists
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            // Debugging: Confirm old image deletion
            dd('Deleting old profile photo...');
            Storage::disk('public')->delete($imagePath);
        }
        $imagePath = $request->file('profile_photo_path')->store('users', 'public');
    }

    // Prepare the update data
    $updateData = [
        'name' => $request->input('name'),
        'phone_number' => $request->input('phone_number'),
        'email' => $request->input('email'),
        'status' => $request->input('status', 'active'),
        'profile_photo_path' => $imagePath,
        'dob' => $request->input('dob'), 
    ];

    // Debugging: Check updated data
   

    // Only update password if provided
    if ($request->filled('password')) {
        $updateData['password'] = Hash::make($request->input('password'));
    }

    // Update the user
    $user->update($updateData);

    // Debugging: Confirm user updated
  
    if ($request->filled('role')) {  // Check if a new role was selected
        $role = Role::find($request->role);  // Use the correct key "role"
        
        if ($role) { // Only update if role exists
            $user->syncRoles([$role]); 
        }
    }
    // Redirect with success message
    return redirect()->route('app-user-list')->with('success', 'User updated successfully!');
}
    public function delete(Request $request){
        $this->authorize('delete users');
        $id = $request->query('id');
        $user = User::FindOrFail($id);
        if ($user) {
            $user->delete();
            return redirect()->route('app-user-list')->with('success', 'user deleted successfully!');
        }
    }
}
