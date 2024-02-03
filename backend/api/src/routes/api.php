<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', RegisterController::class)->name('api.auth.register');
Route::post('auth/login', LoginController::class)->name('api.auth.login');

Route::group([
    'middleware' => ['auth:api']
], function() {
    Route::get('auth/profile', [ProfileController::class, 'profile'])->name('api.auth.profile');
    Route::get('auth/refresh-token', [RefreshTokenController::class, 'refreshToken'])->name('api.auth.refreshToken');
    Route::get('auth/logout', [LogoutController::class, 'logout'])->name('api.auth.logout');
});
