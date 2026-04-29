<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;

#[Fillable([
    'parent_id',
    'type',
    'title',
    'icon',
    'route',
    'url',
    'permission',
    'badge_text',
    'badge_class',
    'sort_order',
    'is_active',
    'open_new_tab'
])]
class MenuItem extends Model
{
    protected $casts = [
        'is_active'    => 'boolean',
        'open_new_tab' => 'boolean',
        'sort_order'   => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    /** Resolve href: named route → custom url → '#' */
    public function getHrefAttribute(): string
    {
        if ($this->route) {
        // Delete wildcard suffix '.*' if present to find base route
        $routeName = str_ends_with($this->route, '.*')
            ? substr($this->route, 0, -2)  // delete exactly 2 characters from the end '.*'
            : $this->route;

        if (\Route::has($routeName)) {
            return route($routeName);
        }

        // Wildcard: get first matching route if route name ends with '.*'
        if (str_contains($this->route, '*')) {
            $prefix    = substr($this->route, 0, -2);
            $matched   = collect(\Route::getRoutes()->getRoutesByName())
                ->keys()
                ->first(fn($name) => str_starts_with($name, $prefix));

            if ($matched) {
                return route($matched);
            }
        }
    }

    return $this->url ?: '#';
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        if ($this->route) {
            return request()->routeIs($this->route);
        }
        if ($this->url && $this->url !== '#') {
            return request()->is(ltrim($this->url, '/'))
                || request()->is(ltrim($this->url, '/') . '/*');
        }
        return false;
    }

    public function hasActiveChild(): bool
    {
        foreach ($this->children as $child) {
            if ($child->isActive() || $child->hasActiveChild()) {
                return true;
            }
        }
        return false;
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }
}
