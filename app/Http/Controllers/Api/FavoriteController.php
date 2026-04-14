<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FavoriteController extends Controller
{
    public function store(Request $request, Recipe $recipe): JsonResponse
    {
        $request->user()->favorites()->firstOrCreate([
            'recipe_id' => $recipe->id,
        ]);

        return response()->json(['message' => 'Añadido a favoritos.']);
    }

    public function destroy(Request $request, Recipe $recipe): JsonResponse
    {
        $request->user()->favorites()
            ->where('recipe_id', $recipe->id)
            ->delete();

        return response()->json(['message' => 'Eliminado de favoritos.']);
    }

    public function index(Request $request): JsonResponse
    {
        $recipes = Recipe::published()
            ->whereHas('favorites', fn($q) => $q->where('user_id', $request->user()->id))
            ->withCount('likes')
            ->get();

        return response()->json(['data' => $recipes]);
    }
}
