<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{
    public function __construct(protected MenuService $menuService) {}

    public function index()
    {
        $menuTree    = $this->menuService->getAllNested();
        $allFlat     = $this->menuService->getAllFlat();
        $permissions = Permission::orderBy('name')->pluck('name');

        return view('admin.menu.builder', compact('menuTree', 'allFlat', 'permissions'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'         => 'required|in:title,item',
            'title'        => 'required|string|max:100',
            'icon'         => 'nullable|string|max:100',
            'route'        => 'nullable|string|max:200',
            'url'          => 'nullable|string|max:500',
            'permission'   => 'nullable|string|max:200',
            'badge_text'   => 'nullable|string|max:30',
            'badge_class'  => 'nullable|string|max:50',
            'is_active'    => 'boolean',
            'open_new_tab' => 'boolean',
            'parent_id'    => 'nullable|exists:menu_items,id',
        ]);

        // Ensure route name exists if type is item
        if ($data['type'] === 'item' && ! empty($data['route'])) {
            if (! $this->validateRoute($data['route'])) {
                return response()->json(['success' => false, 'message' => __('The selected route does not exist.')], 422);
            }
        }

        $data['sort_order'] = MenuItem::where('parent_id', $data['parent_id'] ?? null)
            ->max('sort_order') + 1;
        $item = MenuItem::create($data);

        return response()->json(['success' => true, 'message' => __('Menu created.'), 'item' => $item]);
    }

    public function show(MenuItem $item): JsonResponse
    {
        return response()->json($item);
    }

    public function update(Request $request, MenuItem $item): JsonResponse
    {
        $data = $request->validate([
            'type'         => 'required|in:title,item',
            'title'        => 'required|string|max:100',
            'icon'         => 'nullable|string|max:100',
            'route'        => 'nullable|string|max:200',
            'url'          => 'nullable|string|max:500',
            'permission'   => 'nullable|string|max:200',
            'badge_text'   => 'nullable|string|max:30',
            'badge_class'  => 'nullable|string|max:50',
            'is_active'    => 'boolean',
            'open_new_tab' => 'boolean',
        ]);

        // Ensure route name exists if type is item
        if ($data['type'] === 'item' && ! empty($data['route'])) {
            if (! $this->validateRoute($data['route'])) {
                return response()->json(['success' => false, 'message' => __('The selected route does not exist.')], 422);
            }
        }

        $item->update($data);

        return response()->json(['success' => true, 'message' => __('Menu updated.'), 'item' => $item->fresh()]);
    }

    public function destroy(MenuItem $item): JsonResponse
    {
        $item->delete();
        return response()->json(['success' => true]);
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate(['order' => 'required|string']);
        $order = json_decode($request->input('order'), true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($order)) {
            return response()->json(['success' => false, 'title' => __('Error'), 'message' => __('Invalid data.')], 422);
        }

        DB::transaction(fn() => $this->menuService->saveOrder($order));

        $this->menuService->clearCache();

        return response()->json(['success' => true, 'title' => __('Success'), 'message' => __('Menu saved.')]);
    }

    public function toggle(MenuItem $item): JsonResponse
    {
        $item->update(['is_active' => ! $item->is_active]);
        return response()->json(['success' => true, 'is_active' => $item->is_active]);
    }

    private function validateRoute(string $route): bool
    {
        // Exact route
        if (! str_contains($route, '*')) {
            return \Route::has($route);
        }

        // Delete any trailing '.*' for wildcard routes
        $prefix = rtrim($route, '.*');

        return collect(\Route::getRoutes()->getRoutesByName())
            ->keys()
            ->contains(fn($name) => str_starts_with($name, $prefix));
    }
}
