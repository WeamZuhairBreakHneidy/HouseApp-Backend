<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HouseController;
use App\Http\Controllers\API\FeaturedHouseController;

use App\Http\Controllers\API\ArchitectController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/houses/search', [HouseController::class, 'search']);
Route::get('/houses', [HouseController::class, 'index']);
Route::get('/houses/{house}', [HouseController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::middleware('admin')->group(function () {
        Route::post('/houses', [HouseController::class, 'store']);
        Route::put('/houses/{house}', [HouseController::class, 'update']);
        Route::delete('/houses/{house}', [HouseController::class, 'destroy']);
    });
});

// Public GET
Route::get('featured-houses', [FeaturedHouseController::class, 'index']);
Route::get('featured-houses/{featuredHouse}', [FeaturedHouseController::class, 'show']);

// Admin-only CRUD
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('featured-houses', [FeaturedHouseController::class, 'store']);
    Route::put('featured-houses/{featuredHouse}', [FeaturedHouseController::class, 'update']);
        Route::delete('featured-houses/{id}', [FeaturedHouseController::class, 'destroy']);
});


// 🟢 متاحة للجميع
Route::get('/architects', [ArchitectController::class, 'index']);
Route::get('/architects/{id}', [ArchitectController::class, 'show']);

// 🔴 للأدمن فقط
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/architects', [ArchitectController::class, 'store']);
    Route::put('/architects/{id}', [ArchitectController::class, 'update']);
    Route::delete('/architects/{id}', [ArchitectController::class, 'destroy']);
});
