<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/auth')->group( function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/error', [AuthController::class, 'error'])->name('error');
});

Route::get('/items', [ItemsController::class, 'index']);
Route::prefix('/items')->group( function() {
    Route::get('/{id}', [ItemsController::class, 'show']);
});

Route::middleware('auth:api')->group( function() {
    Route::prefix('/auth')->group( function() {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
    Route::prefix('/items')->group( function() {
        Route::post('/store', [ItemsController::class, 'store']);
        Route::post('/update/{id}', [ItemsController::class, 'update']);
        Route::post('/delete/{id}', [ItemsController::class, 'destroy']);
    });
});