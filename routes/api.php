<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\DataEntryController;
use App\Http\Controllers\KindOfAnimalController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GetYearlyDataController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\BarangayCountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalDataController;
use App\Http\Controllers\BarangayAnimalController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserProfileController;

use App\Http\Controllers\FarmerDataController;

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

Route::post('/dataEntry', [DataEntryController::class, 'store']);

Route::get('/Barangay/Data', [BarangayCountController::class, 'getBarangayCounts']);

Route::get('/animaldata', [AnimalDataController::class, 'getAnimalData']);

Route::get('/barangay/data', [BarangayAnimalController::class, 'getBarangayData']);
// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/animal', [AnimalController::class, 'store']);
    Route::get('/animal/count', [AnimalController::class, 'getAnimalCount']);
    Route::get('/getanimal', [AnimalController::class, 'index']);
    
    Route::post('/kfanimal', [KindOfAnimalController::class, 'store']);
    Route::get('/getkfanimal', [KindOfAnimalController::class, 'index']);
    Route::get('/profile', [UserAuthController::class, 'getUserInfo']);
    
    Route::get('/livestock/total', [GetYearlyDataController::class, 'getTotalLivestockData']);
    Route::get('/livestock/currentyeartotal', [GetYearlyDataController::class, 'getTotalLivestockDataForCurrentYear']);
    Route::get('/livestock/YearsData', [GetYearlyDataController::class, 'getTotalLivestockDataByYear']);
    Route::get('/livestock/TopBarangay', [GetYearlyDataController::class, 'getTopBarangaysWithMostLivestock']);
    Route::get('/livestock/LiveStockInsights', [GetYearlyDataController::class, 'generateInsights']);

    // All Farmer Routes
    
Route::prefix('{type}/data')->group(function () {
    Route::get('/', [FarmerDataController::class, 'index']);
    Route::post('/', [FarmerDataController::class, 'store']);
    Route::get('/{id}', [FarmerDataController::class, 'show']);
    Route::put('/{id}', [FarmerDataController::class, 'update']);
    Route::delete('/{id}', [FarmerDataController::class, 'destroy']);
    });
    

    // Add more protected routes here if needed

    Route::post('/user/profile', [UserProfileController::class, 'storeOrUpdate']);
    Route::get('/user/profile', [UserProfileController::class, 'show']);
    Route::get('/usermanagement/data', [UserManagementController::class, 'getAllUserInfo']);
    Route::delete('/usermanagement/delete/{id}', [UserManagementController::class, 'deleteUser']);
    Route::put('/usermanagement/change-type/{id}', [UserManagementController::class, 'changeUserType']);
});
Route::middleware('auth:sanctum')->get('/user', [UserAuthController::class, 'getUserInfo']);

Route::middleware('auth:sanctum')->post('/logout', [UserAuthController::class, 'logout']);
