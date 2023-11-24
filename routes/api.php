<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\LandController;
use App\Http\Controllers\LocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Broadcast;

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
    Route::get('land/user', [LandController::class, 'getUserLands']);
    Route::get('land/user/closer-lands', [LandController::class, 'getCloserLands']);
    Route::apiResource('land',LandController::class)->except(['update']);
    Route::post('land/{land}', [LandController::class, 'update']);
});

Route::get('/foto-tanah', [LandController::class, 'getLandPhoto']);

// Route::get('/pusherAuth', [UserController::class, 'pusherAuth']);
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

Route::prefix('location')
    ->group(function () {
        Route::get('/provinces',[LocationController::class, 'getAllProvinces']);
        Route::get('/province/{prov_id}/cities',[LocationController::class, 'getAllCities']);
        Route::get('/city/{city_id}/districts', [LocationController::class, 'getAllDistricts']);
        Route::get('/district/{dis_id}/subdistricts', [LocationController::class, 'getAllSubDistricts']);
        Route::get('/{subdistrict}', [LocationController::class, 'getAllDataLocation']);
    });
