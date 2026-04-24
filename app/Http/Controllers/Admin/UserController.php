<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                ->select('users.id', 'users.username', 'users.email', 'user_profiles.full_name')
                ->orderBy('users.id')
                ->with('roles')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('roles_list', function ($user) {
                    $roles = $user->roles->pluck('name')->toArray();

                    $el = '<input type="hidden" class="roles-data" value="' . implode(',', $roles) . '">';
                    if (empty($roles)) {
                        $el .= '<span class="text-muted">' . __('No roles assigned') . '</span>';
                    }
                    if (count($roles) > 1) {
                        $displayRoles = array_slice($roles, 0, 1)[0];
                        $remainingRoles = array_slice($roles, 1);
                        $remainingCount = count($remainingRoles);
                        $rolesHtml = '<div class="d-flex flex-column gap-1">';
                        foreach ($remainingRoles as $role) {
                            $rolesHtml .= '<span class="badge bg-secondary-subtle text-secondary fw-medium">' . $role . '</span>';
                        }
                        $rolesHtml .= '</div>';
                        $el .= '<span class="badge bg-secondary-subtle text-secondary fw-medium me-1">' . $displayRoles . '</span>';
                        $el .= '<span tabindex="0" class="badge bg-secondary-subtle text-secondary" role="button" data-bs-toggle="popover" data-bs-trigger="focus" title="' . __('More Roles') . '" data-bs-content="' . e($rolesHtml) . '" data-bs-html="true">+' . $remainingCount . '</span>';
                        return $el;
                    }
                    if (count($roles) === 1) {
                        $el .= '<span class="badge bg-secondary-subtle text-secondary fw-medium">' . $roles[0] . '</span>';
                    }

                    return $el;
                })
                ->addColumn('actions', function ($user) {
                    $buttons = '<button data-id="' . $user->id . '" type="button" class="btn btn-sm btn-warning btn-icon waves-effect waves-light btn-edit"><i class="bx bxs-pencil" title="' . __('Edit') . '"></i></button>';
                    return $buttons;
                })
                ->rawColumns(['actions', 'roles_list'])
                ->make(true);
        }

        $roles = Role::orderBy('id')->get();
        return view('admin.users.index', compact('roles'));
    }

    public function update(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'roles'   => 'array',
                'roles.*' => 'string|exists:roles,name',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'title' => 'Error',
                    'message' => __('The given data was invalid.'),
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user->syncRoles($request->input('roles', []));

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return response()->json([
                'status'  => 'success',
                'title'   => __('Success'),
                'message' => __('User roles updated successfully.'),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'title' => __('Error'),
                'message' => __('An error occurred while updating user roles.'),
            ], 500);
        }
    }
}
