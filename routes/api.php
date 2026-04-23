<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\StreetController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and are assigned to
| the "api" middleware group. They all automatically have the "/api" prefix.
|
*/

// API Version 1
Route::prefix('v1')->name('api.v1.')->group(function () {

    // Public authentication routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {

        // Auth routes
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        // Streets API
        Route::apiResource('streets', StreetController::class);

        // Projects API
        Route::apiResource('projects', ProjectController::class);

        // Tasks API (nested under projects)
        Route::apiResource('projects.tasks', TaskController::class);
    });
});
