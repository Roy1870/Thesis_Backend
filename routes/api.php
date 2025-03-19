<?php

use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FarmerDataController;
use App\Http\Controllers\LivestockRecordController; // ✅ Import the controller

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These 
| routes are loaded by the RouteServiceProvider and all of them will 
| be assigned to the "api" middleware group. Make something great! 
|
*/

// Public routes (no authentication needed)
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserAuthController::class, 'login']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    // All Farmer Routes
    Route::prefix('{type}/data')->group(function () {
        Route::get('/', [FarmerDataController::class, 'index']);
        Route::post('/', [FarmerDataController::class, 'store']);
        Route::get('/{id}', [FarmerDataController::class, 'show']);
        Route::put('/{id}', [FarmerDataController::class, 'update']);
        Route::delete('/{id}', [FarmerDataController::class, 'destroy']);
    });

    // ✅ Livestock Records CRUD Routes
    Route::prefix('livestock-records')->group(function () {
        Route::get('/', [LivestockRecordController::class, 'index']);      // Get all records
        Route::post('/', [LivestockRecordController::class, 'store']);     // Create a record
        Route::get('/{id}', [LivestockRecordController::class, 'show']);   // Get a single record
        Route::put('/{id}', [LivestockRecordController::class, 'update']); // Update a record
        Route::delete('/{id}', [LivestockRecordController::class, 'destroy']); // Delete a record
    });

    // User Profile & Management Routes
    Route::post('/user/profile', [UserProfileController::class, 'storeOrUpdate']);
    Route::get('/user/profile', [UserProfileController::class, 'show']);
    Route::get('/usermanagement/data', [UserManagementController::class, 'getAllUserInfo']);
    Route::delete('/usermanagement/delete/{id}', [UserManagementController::class, 'deleteUser']);
    Route::put('/usermanagement/change-type/{id}', [UserManagementController::class, 'changeUserType']);
});

// Authentication routes
Route::middleware('auth:sanctum')->get('/user', [UserAuthController::class, 'getUserInfo']);
Route::middleware('auth:sanctum')->post('/logout', [UserAuthController::class, 'logout']);
