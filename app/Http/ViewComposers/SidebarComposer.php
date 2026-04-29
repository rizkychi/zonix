<?php

namespace App\Http\ViewComposers;

use App\Services\MenuService;
use Illuminate\View\View;

class SidebarComposer
{
    public function __construct(protected MenuService $menuService) {}

    public function compose(View $view): void
    {
        $view->with('sidebarMenu',
            $this->menuService->getMenuForUser(auth()->user())
        );
    }
}