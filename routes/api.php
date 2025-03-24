<?php

use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FarmerDataController;
use App\Http\Controllers\LivestockRecordsController;
use App\Http\Controllers\RaiserController;
use App\Http\Controllers\GrowerController;
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

// Public routes (no authentication needed)
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserAuthController::class, 'login']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('farmers')->group(function () {
        Route::get('/', [FarmerDataController::class, 'index']);
        Route::post('/', [FarmerDataController::class, 'store']);
        Route::get('/{id}', [FarmerDataController::class, 'show']);
        Route::put('/{id}', [FarmerDataController::class, 'update']);
        Route::delete('/{id}', [FarmerDataController::class, 'destroy']);
    });
    
    // Routes for deleting individual Crops & Rice
    Route::delete('/crops/{id}', [FarmerDataController::class, 'deleteCrop']);
    Route::delete('/rice/{id}', [FarmerDataController::class, 'deleteRice']);
   

    Route::prefix('operators')->group(function () {
        Route::get('/', [OperatorController::class, 'index']);           // Get all operators
        Route::post('/', [OperatorController::class, 'store']);          // Create a new operator
        Route::get('/{id}', [OperatorController::class, 'show']);        // Get a specific operator
        Route::put('/{id}', [OperatorController::class, 'update']);      // Update an operator
        Route::delete('/{id}', [OperatorController::class, 'destroy']);  // Delete an operator
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
