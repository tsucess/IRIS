<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\UserSearchController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResidentExtendedController;
use App\Http\Controllers\ResidentImportController;
use App\Http\Controllers\SessionManagementController;
use App\Http\Controllers\StreetController;
use App\Http\Controllers\Superadmin\SystemSettingsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TwoFactorController;
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

    // File Attachments (polymorphic)
    Route::post('/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');

    // Comments (polymorphic)
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Complaints / Service Requests
    Route::resource('complaints', ComplaintController::class);

    // In-App Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Session Management
    Route::get('/sessions', [SessionManagementController::class, 'index'])->name('sessions.index');
    Route::delete('/sessions/{sessionId}', [SessionManagementController::class, 'destroy'])->name('sessions.destroy');

    // Two-Factor Authentication
    Route::get('/two-factor/setup', [TwoFactorController::class, 'setup'])->name('two-factor.setup');
    Route::post('/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('/two-factor/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');
    Route::get('/two-factor/verify', [TwoFactorController::class, 'showVerify'])->name('two-factor.verify');
    Route::post('/two-factor/verify', [TwoFactorController::class, 'verify'])->name('two-factor.verify.submit');

    // Gantt / Calendar View
    Route::get('/calendar', [ProjectController::class, 'calendar'])->name('projects.calendar');

    // Community Announcements
    Route::resource('announcements', AnnouncementController::class);
});

// Superadmin-only routes
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/settings', [SystemSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/clear-cache', [SystemSettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::patch('/settings/users/{user}/promote', [SystemSettingsController::class, 'promoteToAdmin'])->name('settings.promote');
    Route::patch('/settings/users/{user}/demote', [SystemSettingsController::class, 'demoteToUser'])->name('settings.demote');
    Route::patch('/settings/users/{id}/restore', [SystemSettingsController::class, 'restore'])->name('settings.restore');
    // Audit log
    Route::get('/audit-log', [\App\Http\Controllers\Superadmin\AuditLogController::class, 'index'])->name('audit-log');
});

// Resident Import (admin only)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/imports/residents', [ResidentImportController::class, 'showForm'])->name('imports.residents');
    Route::post('/imports/residents', [ResidentImportController::class, 'import'])->name('imports.residents.upload');
});
