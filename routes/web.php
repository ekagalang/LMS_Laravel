<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Rute untuk Area Admin ---
Route::middleware(['auth', 'role:administrator'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Manajemen Pengguna
        Route::get('/users', [AdminUserController::class, 'index']) // << RUTE INI
            ->name('users.index'); 
            
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])
            ->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])
            ->name('users.update');

        Route::resource('users', AdminUserController::class)->except(['show', 'create', 'store', 'destroy']);

        Route::resource('categories', AdminCategoryController::class);

        Route::resource('courses', AdminCourseController::class);
});

// Rute untuk instruktur (jika ada)
Route::middleware(['auth', 'role:instructor'])
    ->prefix('instructor')
    ->name('instructor.')
    ->group(function () {
        Route::get('/dashboard', function () { 
            return "<h1>Dashboard Instruktur</h1>";
        })->name('dashboard');
});

require __DIR__.'/auth.php';
