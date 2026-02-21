<?php
use App\Http\Controllers\Api\RecipeController;
use Orion\Facades\Orion;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\RegisteredUserController;

Route::middleware(['throttle:5,1'])->post('/login', [AuthTokenController::class, 'login']);
Route::middleware(['throttle:5,1'])->post('/register', [RegisteredUserController::class, 'store']);


Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/me', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthTokenController::class, 'logout']);
    Route::get('/my-recipes', [AuthTokenController::class, 'myRecipes']);
});

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Orion::resource('recipes', RecipeController::class);
});
