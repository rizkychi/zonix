<?php

use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
include __DIR__ . '/auth.php';

// Landing page route
Route::get('/', function () {
    $login = '<a href="' . route('login', absolute: false) . '" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>';
    $register = '<a href="' . route('register', absolute: false) . '" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>';
    return 'Welcome to LaravelZon! ' . (Auth::check() ? 'You are logged in as ' . Auth::user()->username . '.' : $login . ' ' . $register);
});

// Dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'root'])->name('dashboard');
    Route::get('/dashboard/{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:super-admin']) // spatie middleware to restrict access to super-admins only
    ->group(function () {

        // Resource management
        Route::get('resources', [ResourceController::class, 'index'])->name('resources.index');
        Route::post('resources/sync', [ResourceController::class, 'sync'])->name('resources.sync');
        Route::patch('resources/{resource}/toggle', [ResourceController::class, 'toggle'])->name('resources.toggle');
        Route::patch('resources/{resource}/group', [ResourceController::class, 'updateGroup'])->name('resources.group');

        // Role management
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::get('roles/{role}/permissions', [RoleController::class, 'editPermissions'])->name('roles.permissions.edit');
        Route::post('roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('roles.permissions.sync');

        // User Role management
        Route::get('user-roles', [UserController::class, 'index'])->name('user-roles.index');
        Route::patch('user-roles/{user}', [UserController::class, 'update'])->name('user-roles.update');
    });
