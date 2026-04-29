<?php

namespace App\Services;

use App\Models\MenuItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    const CACHE_PREFIX = 'sidebar_menu_';
    const CACHE_TTL    = 3600; // 1 hour in seconds

    /**
     * Menu tree has been filtered by user permissions and cached for performance.
     * Unique cache key per user permissions set, so different users get different cached trees.
     */
    public function getMenuForUser($user): Collection
    {
        $cacheKey = self::CACHE_PREFIX . $this->permissionKey($user);

        $store    = Cache::getStore();

        if (method_exists($store, 'tags')) {
            // Redis: cache with tag, so we can flush only sidebar-related cache when menu items are updated
            $data = Cache::tags(['sidebar_menu'])->remember($cacheKey, self::CACHE_TTL, function () use ($user) {
                return $this->buildFilteredTree($user)->toArray();
            });
        } else {
            // File driver
            $data = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
                return $this->buildFilteredTree($user)->toArray();
            });
        }

        // Hydrate array back to Collection of MenuItem models with nested children
        return $this->hydrateTree($data);
    }

    /**
     * Recursively hydrate array → Collection<MenuItem>
     * MenuItem::hydrate() bypass $fillable, set all attribute directly.
     */
    protected function hydrateTree(array $items): Collection
    {
        return collect($items)->map(function (array $data) {
            $childrenData = $data['children'] ?? [];

            // Remove 'children' key
            unset($data['children']);

            // Hydrate single model from raw array (bypass fillable guard)
            $model = MenuItem::hydrate([$data])->first();

            // Remove any existing 'children' relationship to avoid confusion
            if ($model->offsetExists('children')) {
                $model->offsetUnset('children');
            }

            // Set relationship 'children' with recursively hydrated children
            $model->setRelation(
                'children',
                $this->hydrateTree($childrenData)
            );

            return $model;
        });
    }


    /** All items in the nested tree (for builder, without filter). */
    public function getAllNested(): Collection
    {
        return MenuItem::with('children.children.children')
            ->roots()
            ->orderBy('sort_order')
            ->get();
    }

    /** All items flat, keyed by id (for builder dropdown). */
    public function getAllFlat(): Collection
    {
        return MenuItem::orderBy('sort_order')->get()->keyBy('id');
    }

    /**
     * Save the order of items from the Nestable JSON output.
     * Format: [{"id":1,"children":[{"id":2},...]},...]
     */
    public function saveOrder(array $items, ?int $parentId = null): void
    {
        foreach ($items as $sort => $node) {
            MenuItem::where('id', $node['id'])->update([
                'parent_id'  => $parentId,
                'sort_order' => $sort,
            ]);
            if (! empty($node['children'])) {
                $this->saveOrder($node['children'], $node['id']);
            }
        }
    }

    /**
     * Delete all cache entries related to sidebar menu.
     * Called automatically by MenuItemObserver.
     */
    public function clearCache(): void
    {
        $store = Cache::getStore();

        if (method_exists($store, 'tags')) {
            // Redis / Memcached — delete only sidebar-related cache
            Cache::tags(['sidebar_menu'])->flush();
        } else {
            // File driver — flush all cache
            Cache::flush();
        }
    }

    // ── Internal ─────────────────────────────────────────────────────────────

    protected function buildFilteredTree($user): Collection
    {
        $roots = MenuItem::with('children.children.children')
            ->active()
            ->roots()
            ->orderBy('sort_order')
            ->get();

        return $this->filterTree($roots, $user);
    }

    protected function filterTree(Collection $items, $user): Collection
    {
        return $items
            ->filter(fn($item) => $this->canAccess($item, $user))
            ->map(function ($item) use ($user) {
                if ($item->children->isNotEmpty()) {
                    $item->setRelation(
                        'children',
                        $this->filterTree($item->children, $user)
                    );
                }
                return $item;
            })
            ->values();
    }

    protected function canAccess(MenuItem $item, $user): bool
    {
        if (! $item->is_active)  return false;
        if (! $item->permission) return true;
        if (! $user)             return false;
        return $user->can($item->permission); // Spatie
    }

    /**
     * Create a stable cache key based on the user's permission set.
     * Different users with different permissions → different cache entries.
     */
    protected function permissionKey($user): string
    {
        if (! $user) return 'guest';

        $permissions = $user->getAllPermissions()
            ->pluck('name')
            ->sort()
            ->values()
            ->implode('|');

        return md5($user->id . '_' . $permissions);
    }
}
