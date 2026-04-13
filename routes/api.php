<?php

use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\IngredientController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

Route::middleware(['throttle:5,1'])->post('/login', [AuthTokenController::class, 'login']);
Route::middleware(['throttle:5,1'])->post('/register', [RegisteredUserController::class, 'store']);

// Rutas autenticadas
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/me', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthTokenController::class, 'logout']);
    Route::get('/my-recipes', [AuthTokenController::class, 'myRecipes']);

    Orion::resource('recipes', RecipeController::class, [
        'only' => ['store', 'update', 'destroy'],
    ])->withoutBatch();

    Orion::resource('categories', CategoryController::class, [
        'only' => ['store'],
    ])->withoutBatch();

    Orion::resource('ingredients', IngredientController::class, [
        'only' => ['store'],
    ])->withoutBatch();
});

// Rutas públicas
Route::middleware(['throttle:api'])->group(function () {
    Orion::resource('recipes', RecipeController::class, [
        'only' => ['index', 'show', 'search'],
    ])->withoutBatch();

    Orion::resource('categories', CategoryController::class, [
        'only' => ['index', 'show'],
    ])->withoutBatch();

    Orion::resource('ingredients', IngredientController::class, [
        'only' => ['index', 'show'],
    ])->withoutBatch();
});
