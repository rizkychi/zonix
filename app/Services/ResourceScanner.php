<?php

namespace App\Services;

use App\Models\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class ResourceScanner
{
    // Prefixes of route names to skip during scanning (e.g. internal, debug, auth routes)
    protected array $skipPrefixes = [
        'admin.',        // admin panel routes
        'debugbar.',
        'sanctum.',
        'ignition.',
        'livewire.',
        'password.',
        'verification.',
        'default-livewire.',
    ];

    protected array $skipExact = [
        'login', 'logout', 'register', 
    ];

    public function scan(): Collection
    {
        $result = collect();

        foreach (Route::getRoutes() as $route) {
            $routeName = $route->getName();

            if (!$routeName || $this->shouldSkip($routeName)) {
                continue;
            }

            $action = $route->getAction();
            $uses   = $action['uses'] ?? null;

            // Skip closure routes
            if (!$uses || !is_string($uses) || !str_contains($uses, '@')) {
                continue;
            }

            [$controllerClass, $method] = explode('@', $uses);

            $shortName = class_basename($controllerClass);
            $group     = Str::before($shortName, 'Controller');

            $methods    = array_filter($route->methods(), fn($m) => $m !== 'HEAD');
            $httpMethod = implode('|', $methods);

            $result->push([
                'name'               => $routeName,  // route name = permission name
                'route_name'         => $routeName,
                'uri'                => '/' . ltrim($route->uri(), '/'),
                'http_method'        => $httpMethod,
                'controller_class'   => $shortName,
                'controller_action'  => $method,
                'group'              => $group,
            ]);
        }

        return $result;
    }

    public function sync(): array
    {
        $scanned = $this->scan();
        $created = 0;
        $updated = 0;

        foreach ($scanned as $data) {
            $resource = Resource::firstOrNew(['route_name' => $data['route_name']]);
            $isNew    = !$resource->exists;

            // Update fields that are safe to overwrite (name, uri, http_method, controller info)
            $resource->name              = $data['name'];
            $resource->uri               = $data['uri'];
            $resource->http_method       = $data['http_method'];
            $resource->controller_class  = $data['controller_class'];
            $resource->controller_action = $data['controller_action'];

            // Preserve group & is_active for existing resources, set for new ones
            if ($isNew) {
                $resource->group     = $data['group'];
                $resource->is_active = true;
            }

            $resource->save();

            // Ensure a corresponding permission exists for this resource
            Permission::firstOrCreate([
                'name'       => $data['name'],
                'guard_name' => 'web',
            ]);

            $isNew ? $created++ : $updated++;
        }

        // Delete resources that no longer exist in scanned routes
        $scannedNames = $scanned->pluck('route_name')->toArray();
        $deleted = Resource::whereNotIn('route_name', $scannedNames)->count();
        Resource::whereNotIn('route_name', $scannedNames)->delete();

        Resource::clearCache();

        return ['created' => $created, 'updated' => $updated, 'deleted' => $deleted, 'total' => $scanned->count()];
    }

    protected function shouldSkip(string $routeName): bool
    {
        if (in_array($routeName, $this->skipExact, true)) {
            return true;
        }

        foreach ($this->skipPrefixes as $prefix) {
            if (str_starts_with($routeName, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
