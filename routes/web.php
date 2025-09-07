<?php

use App\Http\Controllers\SidebarController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Role-specific dashboard routes
    Route::get('/admin/dashboard', function () {
        return Inertia::render('admin/dashboard');
    })->name('admin.dashboard');
    
    Route::get('/instructor/dashboard', function () {
        return Inertia::render('instructor/dashboard');
    })->name('instructor.dashboard');
    
    Route::get('/student/dashboard', function () {
        return Inertia::render('student/dashboard');
    })->name('student.dashboard');
    
    // Sidebar API endpoint
    Route::get('/api/sidebar-menu', [SidebarController::class, 'index'])->name('sidebar.menu');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
