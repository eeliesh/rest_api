<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// private routes

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/home', [HomeController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::group(['prefix' => 'oauth', 'middleware' => ['web']], function () {
    Route::prefix('google')->group(function () {
        Route::get('/login', [GoogleController::class, 'redirectToGoogle']);
        Route::get('/callback', [GoogleController::class, 'handleGoogleCallback']);
    });
});
