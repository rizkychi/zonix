<?php

namespace App\Http\Middleware;

use App\Models\Resource;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DynamicRbacMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        // No route name → public/framework route, allow
        if (!$routeName) {
            return $next($request);
        }

        // Single cache read, O(1) lookup → fast permission checks
        $resources = Resource::getCached();

        // Route not registered as resource → public route, allow
        if (!$resources->has($routeName)) {
            return $next($request);
        }

        $resource = $resources->get($routeName);

        // Disabled resource → 403 Forbidden
        if (!$resource->is_active) {
            abort(403, __('Resource disabled by administrator.'));
        }

        // Must be authenticated to access any registered resource
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Super-admin bypass permission checks
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // Spatie using internal cache per-user → zero DB query
        if (!$user->can($resource->name)) {
            abort(403, __('You do not have access to this resource.'));
        }

        return $next($request);
    }
}
