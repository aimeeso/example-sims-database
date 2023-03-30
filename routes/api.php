<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [App\Http\Controllers\UserAuthController::class, 'login']);
});

Route::group([
    'middleware' => ['auth:sanctum', 'abilities:2fa'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('/2fa-verify', [App\Http\Controllers\UserAuthController::class, 'verify2fa']);
    Route::post('/2fa-regenerate', [App\Http\Controllers\UserAuthController::class, 'regenerate2fa']);
});

Route::group([
    'middleware' => ['auth:sanctum', 'abilities:user'],
    'prefix' => 'auth'
], function ($router) {
    Route::get('/refresh', [App\Http\Controllers\UserAuthController::class, 'refresh']);
    Route::post('/logout', [App\Http\Controllers\UserAuthController::class, 'logout']);
    Route::get('/profile', [App\Http\Controllers\UserAuthController::class, 'profile']);
});
