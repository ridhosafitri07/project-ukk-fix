<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\PengaduanApiController;
use App\Http\Controllers\Api\UserProfileApiController;
use App\Http\Controllers\Api\ItemApiController;

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

// Public API Routes
Route::prefix('v1')->group(function () {
    // Authentication Routes
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/register', [AuthApiController::class, 'register']);
    
    // Protected Routes (Require Authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // Auth Routes
        Route::post('/logout', [AuthApiController::class, 'logout']);
        Route::get('/user', [AuthApiController::class, 'user']);
        
        // Profile Routes (All roles)
        Route::prefix('profile')->group(function () {
            Route::get('/', [UserProfileApiController::class, 'show']);
            Route::post('/update', [UserProfileApiController::class, 'update']);
            Route::post('/update-photo', [UserProfileApiController::class, 'updatePhoto']);
            Route::post('/change-password', [UserProfileApiController::class, 'changePassword']);
        });
        
        // Item Routes (All roles can view)
        Route::prefix('items')->group(function () {
            Route::get('/', [ItemApiController::class, 'index']);
            Route::get('/{id}', [ItemApiController::class, 'show']);
        });
        
        // Pengaduan Routes (Pengguna Role)
        Route::prefix('pengaduan')->middleware(\App\Http\Middleware\CheckApiRole::class . ':pengguna')->group(function () {
            Route::get('/', [PengaduanApiController::class, 'index']);
            Route::get('/status/{status}', [PengaduanApiController::class, 'getByStatus']);
            Route::get('/{id}', [PengaduanApiController::class, 'show']);
            Route::post('/', [PengaduanApiController::class, 'store']);
            Route::put('/{id}', [PengaduanApiController::class, 'update']);
            Route::delete('/{id}', [PengaduanApiController::class, 'destroy']);
        });
    });
});
