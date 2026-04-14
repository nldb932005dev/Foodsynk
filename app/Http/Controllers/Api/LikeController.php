<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LikeController extends Controller
{
    public function store(Request $request, Recipe $recipe): JsonResponse
    {
        $request->user()->likes()->firstOrCreate([
            'recipe_id' => $recipe->id,
        ]);

        return response()->json([
            'likes_count' => $recipe->likes()->count(),
        ]);
    }

    public function destroy(Request $request, Recipe $recipe): JsonResponse
    {
        $request->user()->likes()
            ->where('recipe_id', $recipe->id)
            ->delete();

        return response()->json([
            'likes_count' => $recipe->likes()->count(),
        ]);
    }
}
