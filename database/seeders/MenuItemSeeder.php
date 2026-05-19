<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuItem::truncate();

        // Section: MENU
        MenuItem::create(['type' => 'title', 'title' => 'Menu', 'sort_order' => 0, 'is_active' => true]);

        // Dashboard
        MenuItem::create(['type' => 'item', 'title' => 'Dashboard', 'icon' => 'ri-dashboard-2-line', 'sort_order' => 1, 'route' => 'dashboard']);

        // Section: ADMINISTRATION
        $adminParent = MenuItem::create([
            'type' => 'item',
            'title' => 'Admin',
            'icon' => 'ri-shield-user-fill',
            'sort_order' => 10
        ]);

        // Admin Sub-items
        $adminItems = array(
            [
                'type' => 'item',
                'title' => 'Menu Builder',
                'icon' => 'ri-menu-add-line',
                'route' => 'admin.menu.index',
                'permission' => 'admin.menu.index',
                'sort_order' => 11,
                'parent_id' => $adminParent->id
            ],
            [
                'type' => 'item',
                'title' => 'Role Management',
                'icon' => 'ri-shield-user-line',
                'route' => 'admin.roles.index',
                'permission' => 'admin.roles.index',
                'sort_order' => 12,
                'parent_id' => $adminParent->id
            ],
            [
                'type' => 'item',
                'title' => 'Resource Management',
                'icon' => 'ri-database-2-line',
                'route' => 'admin.resources.index',
                'permission' => 'admin.resources.index',
                'sort_order' => 13,
                'parent_id' => $adminParent->id
            ],
            [
                'type' => 'item',
                'title' => 'User Role Management',
                'icon' => 'ri-user-settings-line',
                'route' => 'admin.user-roles.index',
                'permission' => 'admin.user-roles.index',
                'sort_order' => 14,
                'parent_id' => $adminParent->id
            ],
            [
                'type' => 'item',
                'title' => 'Translations',
                'icon' => 'ri-translate',
                'route' => 'admin.translations.index',
                'permission' => 'admin.translations.index',
                'sort_order' => 15,
                'parent_id' => $adminParent->id
            ],
            [
                'type' => 'item',
                'title' => 'Settings',
                'icon' => 'ri-settings-3-line',
                'route' => 'admin.settings.index',
                'permission' => 'admin.settings.index',
                'sort_order' => 16,
                'parent_id' => $adminParent->id
            ]
        );

        collect($adminItems)->each(function ($item) {
            MenuItem::create($item);
        });
    }
}
