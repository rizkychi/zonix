<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use SweetAlert2\Laravel\Swal;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::select('id', 'name')->orderBy('id')->withCount('permissions')->withCount('users')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($role) {
                    if (!in_array($role->name, ['super-admin'])) {
                        $buttons = zx_button_icon(route('admin.roles.permissions.edit', $role), true, 'bx bxs-shield-alt-2', 'info', __('Permissions'));
                        $buttons .= zx_button_edit(route('admin.roles.edit', $role));
                        $buttons .= zx_delete_confirm(route('admin.roles.destroy', $role));
                    } else {
                        $buttons = zx_button_icon('#', false, 'bx bxs-shield-alt-2', 'info', __('Permissions'));
                        $buttons .= zx_button_edit('#', false);
                        $buttons .= zx_button_icon('#', false, 'bx bxs-trash', 'danger', __('Delete'));
                    }
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.roles.index');
    }

    public function create()
    {
        return view('admin.roles.form');
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

        return view('admin.roles.form', compact('role', 'resources', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|string|max:100|unique:roles,name,' . $role->id]);

        $role->update(['name' => $request->name]);

        return redirect()->route('admin.roles.index')->with('success', __('Role updated successfully.'));
    }

    public function editPermissions(Role $role)
    {
        $resources = Resource::orderBy('group')
            ->orderByRaw("
                CASE controller_action
                    WHEN 'index' THEN 1
                    WHEN 'create' THEN 2
                    WHEN 'store' THEN 3
                    WHEN 'edit' THEN 4
                    WHEN 'update' THEN 5
                    WHEN 'destroy' THEN 6
                    ELSE 999
                END
            ")
            ->orderBy('controller_action')
            ->get()
            ->groupBy('group');

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.permissions', compact('role', 'resources', 'rolePermissions'));
    }

    public function syncPermissions(Request $request, Role $role)
    {
        $permissions = $request->input('permissions', []);

        // Validate that the provided permissions actually exist in the system
        $valid = Permission::whereIn('name', $permissions)->pluck('name')->toArray();
        $role->syncPermissions($valid);

        // Flush spatie cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // return back()->with('success', __('Permissions synchronized successfully.'));
        return redirect()->route('admin.roles.index')->with('success', __('Permissions synchronized successfully.'));
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['super-admin'])) {
            return redirect()->back()->with('swal_custom_error', __('The super-admin role cannot be deleted.'));
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', __('Role deleted successfully.'));
    }
}
