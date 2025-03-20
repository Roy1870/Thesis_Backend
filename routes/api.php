<?php

use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FarmerDataController;
use App\Http\Controllers\LivestockRecordsController;
use App\Http\Controllers\CropsController;
use App\Http\Controllers\GrowerController;

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

    // Livestock Records Routes
    Route::prefix('livestock-records')->group(function () {
        Route::get('/', [LivestockRecordsController::class, 'index']);
        Route::post('/', [LivestockRecordsController::class, 'store']);
        Route::get('/{id}', [LivestockRecordsController::class, 'show']);
        Route::put('/{id}', [LivestockRecordsController::class, 'update']);
        Route::delete('/{id}', [LivestockRecordsController::class, 'destroy']);
    });

    Route::prefix('growers')->group(function () {
        Route::get('/', [GrowerController::class, 'index']);       // List all growers with crops & rice
        Route::post('/', [GrowerController::class, 'store']);      // Create a new grower with crops & rice
        Route::get('/{id}', [GrowerController::class, 'show']);    // Get a specific grower with crops & rice
        Route::put('/{id}', [GrowerController::class, 'update']);  // Update grower, crops, or rice
        Route::delete('/{id}', [GrowerController::class, 'destroy']); // Delete grower and related records
    });

    // Crop Routes
    Route::prefix('crops')->group(function () {
        Route::get('/', [CropsController::class, 'index']);     
        Route::post('/', [CropsController::class, 'store']);    
        Route::get('/{id}', [CropsController::class, 'show']);  
        Route::put('/{id}', [CropsController::class, 'update']); 
        Route::delete('/{id}', [CropsController::class, 'destroy']); 
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
