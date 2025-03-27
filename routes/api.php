<?php

use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FarmerDataController;
use App\Http\Controllers\LivestockRecordsController;
use App\Http\Controllers\OperatorController;

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

// Public routes (No authentication required)
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserAuthController::class, 'login']);

// Protected routes (Authentication required)
Route::middleware('auth:sanctum')->group(function () {

    // Farmers Module
    Route::prefix('farmers')->group(function () {
        Route::get('/', [FarmerDataController::class, 'index']);
        Route::post('/', [FarmerDataController::class, 'store']);
        Route::get('/{id}', [FarmerDataController::class, 'show']);
        Route::put('/{id}', [FarmerDataController::class, 'update']);
        Route::delete('/{id}', [FarmerDataController::class, 'destroy']);

        // Crops & Rice Routes under Farmers
        Route::post('/{id}/crops', [FarmerDataController::class, 'storeCrops']);
        Route::post('/{id}/rice', [FarmerDataController::class, 'storeRice']);
    });

    // Routes for deleting individual Crops & Rice
    Route::delete('/crops/{id}', [FarmerDataController::class, 'destroyCrop']);
    Route::delete('/rice/{id}', [FarmerDataController::class, 'destroyRice']);

    // Livestock Records Module
    Route::prefix('livestock-records')->group(function () {
        Route::get('/', [LivestockRecordsController::class, 'index']);    // Get all livestock records
        Route::post('/', [LivestockRecordsController::class, 'store']);  // Create new livestock record
        Route::get('/{id}', [LivestockRecordsController::class, 'show']); // Get specific livestock record
        Route::put('/{id}', [LivestockRecordsController::class, 'update']); // Update livestock record
        Route::delete('/{id}', [LivestockRecordsController::class, 'destroy']); // Delete livestock record
    });

    // Operators Module
    Route::prefix('operators')->group(function () {
        Route::get('/', [OperatorController::class, 'index']);           
        Route::post('/', [OperatorController::class, 'store']);          
        Route::get('/{id}', [OperatorController::class, 'show']);        
        Route::put('/{id}', [OperatorController::class, 'update']);      
        Route::delete('/{id}', [OperatorController::class, 'destroy']);  
    });

    // User Profile & Management Routes
    Route::prefix('user')->group(function () {
        Route::post('/profile', [UserProfileController::class, 'storeOrUpdate']);
        Route::get('/profile', [UserProfileController::class, 'show']);
    });

    Route::prefix('usermanagement')->group(function () {
        Route::get('/data', [UserManagementController::class, 'getAllUserInfo']);
        Route::delete('/delete/{id}', [UserManagementController::class, 'deleteUser']);
        Route::put('/change-type/{id}', [UserManagementController::class, 'changeUserType']);
    });

    // Authentication routes
    Route::get('/user', [UserAuthController::class, 'getUserInfo']);
    Route::post('/logout', [UserAuthController::class, 'logout']);
});
