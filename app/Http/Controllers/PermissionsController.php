<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PermissionsController extends Controller
{
    use AuthorizesRequests;
    public function seedPermissions()
    {
        $permissions = [
            'view users', 'edit users', 'delete users', 'create users',
            'view products', 'edit products', 'delete products', 'create products',
            'view categories', 'edit categories', 'delete categories', 'create categories',
            'view roles', 'edit roles', 'delete roles', 'create roles',
            'view permissions', 'edit permissions', 'delete permissions', 'create permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        return response()->json(['message' => 'Permissions seeded successfully']);
    }

    public function index()
  {
    $permissions = Permission::with(['users', 'roles.users'])->get();
    return view('content.apps.app-access-permission', compact('permissions'));
  }
}
