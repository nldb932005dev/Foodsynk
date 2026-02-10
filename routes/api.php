<?php
use App\Http\Controllers\Api\RecipeController;
use Orion\Facades\Orion;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthTokenController;


Route::middleware(['throttle:api'])->post('/login', [AuthTokenController::class, 'login']);


Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/me', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthTokenController::class, 'logout']);
    Route::get('/my-recipes', [AuthTokenController::class, 'myRecipes']);
});

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Orion::resource('recipes', RecipeController::class);
});
