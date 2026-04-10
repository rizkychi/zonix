<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles     = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles'   => 'array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        $user->syncRoles($request->input('roles', []));

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', __('User roles updated successfully.'));
    }
}
