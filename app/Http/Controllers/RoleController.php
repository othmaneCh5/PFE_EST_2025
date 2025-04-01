<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RoleController extends Controller
{
    use AuthorizesRequests;
    public function index(){
        $users = \App\Models\User::all();
        $roles = \Spatie\Permission\Models\Role::all();
        $permissions = \Spatie\Permission\Models\Permission::all();
        return view('content.apps.app-access-roles', compact('users', 'roles', 'permissions'));
    }
    public function create_role(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'string|max:255',
        ]);
        $role = \Spatie\Permission\Models\Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->back()->with('success', 'Role created successfully');
    }
}
