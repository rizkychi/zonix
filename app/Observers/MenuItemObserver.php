<?php

namespace App\Observers;

use App\Models\MenuItem;
use App\Services\MenuService;

class MenuItemObserver
{
    public function __construct(protected MenuService $menuService) {}

    public function created(MenuItem $item): void  { $this->menuService->clearCache(); }
    public function updated(MenuItem $item): void  { $this->menuService->clearCache(); }
    public function deleted(MenuItem $item): void  { $this->menuService->clearCache(); }
    public function restored(MenuItem $item): void { $this->menuService->clearCache(); }
}