<?php

namespace App\Providers;

use App\Http\ViewComposers\SidebarComposer;
use App\Models\MenuItem;
use App\Observers\MenuItemObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\MenuService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-admin')) {
                return true; // Super admins bypass all permission checks
            }
        });
        
        // Inject $sidebarMenu to partial sidebar
        View::composer('components.sidebar', SidebarComposer::class);

        // Auto-clear cache when menu items are updated
        MenuItem::observe(MenuItemObserver::class);
    }
}
