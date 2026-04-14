<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\IngredientController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\MenuController;
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

    // Likes
    Route::post('/recipes/{recipe}/like', [LikeController::class, 'store']);
    Route::delete('/recipes/{recipe}/like', [LikeController::class, 'destroy']);

    // Favoritos
    Route::post('/recipes/{recipe}/favorite', [FavoriteController::class, 'store']);
    Route::delete('/recipes/{recipe}/favorite', [FavoriteController::class, 'destroy']);
    Route::get('/my-favorites', [FavoriteController::class, 'index']);

    // Comentarios (escritura)
    Route::post('/recipes/{recipe}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // Menús (privados, solo owner)
    Orion::resource('menus', MenuController::class)->withoutBatch();
    Route::get('/menus/{menu}/shopping-list', [MenuController::class, 'shoppingList']);
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

    // Comentarios (lectura pública)
    Route::get('/recipes/{recipe}/comments', [CommentController::class, 'index']);
});

// Rutas admin
Route::middleware(['auth:sanctum', 'admin', 'throttle:api'])->prefix('admin')->group(function () {
    // Categorías
    Route::get('/categories', [AdminController::class, 'categories']);
    Route::patch('/categories/{category}/approve', [AdminController::class, 'approveCategory']);
    Route::patch('/categories/{category}/reject', [AdminController::class, 'rejectCategory']);
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory']);

    // Ingredientes
    Route::get('/ingredients', [AdminController::class, 'ingredients']);
    Route::delete('/ingredients/{ingredient}', [AdminController::class, 'destroyIngredient']);

    // Recetas
    Route::get('/recipes', [AdminController::class, 'recipes']);
    Route::delete('/recipes/{recipe}', [AdminController::class, 'destroyRecipe']);

    // Usuarios
    Route::get('/users', [AdminController::class, 'users']);

    // Comentarios
    Route::get('/comments', [AdminController::class, 'comments']);
    Route::delete('/comments/{comment}', [AdminController::class, 'destroyComment']);
});
