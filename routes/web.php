<?php

use Illuminate\Support\Facades\Route;

// Authentication Routes
include __DIR__.'/auth.php';

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

    // Admin
    // Route::middleware('role:super-admin')->prefix('admin')->name('admin.')->group(function () {
    //     Route::get('resources', [ResourceSyncController::class, 'index'])->name('resources.index');
    //     Route::post('resources/sync', [ResourceSyncController::class, 'sync'])->name('resources.sync');
    //     Route::patch('resources/{resource}/toggle', [ResourceSyncController::class, 'toggle'])->name('resources.toggle');
    // });
});