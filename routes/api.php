<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatMessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Add routes that require authentication here
    Route::apiResource('chat', ChatController::class)->only(['index', 'show','store']);
    Route::apiResource('chat_message', ChatMessageController::class)->only(['index','store']);
});

Route::prefix('user')
    ->as('user.')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware('auth:sanctum')->name('index');
        Route::get('/photo-profile', [UserController::class, 'getPhotoProfile'])->name('profile_image');
        Route::post('/register', [UserController::class, 'register'])->name('register');
        Route::post('/login', [UserController::class, 'login'])->name('login');
        Route::get('/current-user', [UserController::class, 'get'])->middleware('auth:sanctum')->name('get_current_user');
        Route::post('/update', [UserController::class, 'update'])->middleware('auth:sanctum')->name('update');
        Route::delete('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
    });
