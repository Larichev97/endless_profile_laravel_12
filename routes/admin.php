<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // Dashboard:
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('admin.dashboard');

    require __DIR__.'/admin/profile.php';
    require __DIR__.'/admin/project-settings.php';
});
