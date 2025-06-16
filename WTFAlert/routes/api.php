<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AlerteController;

// Routes publiques
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);

// Routes protégées par JWT
Route::middleware('auth:sanctum')->group(function () {
    // Authentification
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    
    // Alertes
    Route::apiResource('alertes', AlerteController::class);
    Route::get('photos/{id}', [AlerteController::class, 'getPhoto']);
    Route::get('/alertes/mobile', [AlerteController::class, 'mobileAlertes']);
});
