<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->withCount('users')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:roles,name']);

        Role::create(['name' => $request->name, 'guard_name' => 'web']);

        return redirect()->route('admin.roles.index')->with('success', __('Role created successfully.'));
    }

    public function edit(Role $role)
    {
        $resources        = Resource::orderBy('group')->orderBy('controller_action')->get()->groupBy('group');
        $rolePermissions  = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'resources', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|string|max:100|unique:roles,name,' . $role->id]);

        $role->update(['name' => $request->name]);

        return back()->with('success', __('Role updated successfully.'));
    }

    public function syncPermissions(Request $request, Role $role)
    {
        $permissions = $request->input('permissions', []);

        // Validate that the provided permissions actually exist in the system
        $valid = Permission::whereIn('name', $permissions)->pluck('name')->toArray();
        $role->syncPermissions($valid);

        // Flush spatie cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', __('Permissions synchronized successfully.'));
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['super-admin'])) {
            return back()->with('error', __('This role cannot be deleted.'));
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', __('Role deleted.'));
    }
}
