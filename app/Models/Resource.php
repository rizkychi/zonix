<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

#[Fillable([
    'name',
    'route_name',
    'uri',
    'http_method',
    'controller_class',
    'controller_action',
    'group',
    'is_active',
    'description'
])]
class Resource extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Load all resources into cache for quick access during permission checks.
     * Single cache hit per request → O(1) lookup.
     */
    public static function getCached(): Collection
    {
        // Save as plain array in cache to avoid serialization issues with Eloquent models
        $raw = Cache::remember('rbac.resources', now()->addHour(), function () {
            return static::all()->toArray(); // plain PHP array
        });

        // Convert to collection of stdClass objects. not included in cache to avoid serialization issues.
        return collect($raw)->map(fn($item) => (object) $item)->keyBy('route_name');
    }

    public static function clearCache(): void
    {
        Cache::forget('rbac.resources');
    }
}
