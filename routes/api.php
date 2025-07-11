<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HouseController;

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
