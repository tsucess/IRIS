<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\UserSearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResidentExtendedController;
use App\Http\Controllers\StreetController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authenticated user routes (profile, extended profile, exports, search)
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/id-card', [ProfileController::class, 'idCard'])->name('profile.idcard');
    Route::get('/profile/id-card/pdf', [ProfileController::class, 'downloadIdCard'])->name('profile.idcard.download');
    Route::get('/profile/extended', [ResidentExtendedController::class, 'edit'])->name('profile.extended.edit');
    Route::post('/profile/extended', [ResidentExtendedController::class, 'update'])->name('profile.extended.update');
    Route::get('/users/search-results', [UserSearchController::class, 'search'])->name('users.search.results');
    Route::get('/exports/users', [UserManagementController::class, 'exportView'])->name('exports');
    Route::get('/exports/users/download', [UserManagementController::class, 'export'])
        ->name('exports.users.download')
        ->middleware('throttle:10,1');
});

// Admin routes (user management, ID cards, extended profiles, search)
Route::middleware(['auth', 'admin', 'throttle:100,1'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/idcard', [UserManagementController::class, 'idCard'])->name('users.idcard');
    Route::get('/users/{user}/idcard/download', [UserManagementController::class, 'downloadIdCard'])->name('users.idcard.download');
    Route::get('/users/{user}/extended', [ResidentExtendedController::class, 'adminEdit'])->name('users.extended.edit');
    Route::post('/users/{user}/extended', [ResidentExtendedController::class, 'adminUpdate'])->name('users.extended.update');
    Route::get('/users/search', [UserSearchController::class, 'index'])->name('users.search');
    Route::get('/users/search-results', [UserSearchController::class, 'search'])->name('users.search.results');
    Route::get('/users/{user}/view', [UserSearchController::class, 'view'])->name('users.view');
});

require __DIR__.'/auth.php';

// Streets (admin only)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('streets', StreetController::class);
});

// Projects and Tasks (all authenticated, verified users)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/users', [ProjectController::class, 'assignUsers'])->name('projects.users.assign');
    Route::delete('/projects/{project}/users/{user}', [ProjectController::class, 'removeUser'])->name('projects.users.remove');

    // Tasks within projects
    Route::prefix('projects/{project}')->group(function () {
        Route::resource('tasks', TaskController::class);
        Route::get('/tasks/{task}', [ProjectController::class, 'getTask'])->name('projects.tasks.get');
        Route::post('/tasks', [ProjectController::class, 'addTask'])->name('projects.tasks.add');
        Route::put('/tasks/{task}', [ProjectController::class, 'updateTask'])->name('projects.tasks.update');
        Route::delete('/tasks/{task}', [ProjectController::class, 'deleteTask'])->name('projects.tasks.delete');
    });
});
