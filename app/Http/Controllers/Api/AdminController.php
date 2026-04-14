<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    // ── Categorías ────────────────────────────────────────────────────────────

    public function categories(): JsonResponse
    {
        $categories = Category::orderBy('status')->orderBy('name')->get();

        return response()->json(['data' => $categories]);
    }

    public function approveCategory(Category $category): JsonResponse
    {
        $category->update(['status' => 'approved']);

        return response()->json(['data' => $category]);
    }

    public function rejectCategory(Category $category): JsonResponse
    {
        $category->update(['status' => 'rejected']);

        return response()->json(['data' => $category]);
    }

    public function destroyCategory(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(['message' => 'Categoría eliminada.']);
    }

    // ── Ingredientes ──────────────────────────────────────────────────────────

    public function ingredients(): JsonResponse
    {
        $ingredients = Ingredient::orderBy('nombre')->get();

        return response()->json(['data' => $ingredients]);
    }

    public function destroyIngredient(Ingredient $ingredient): JsonResponse
    {
        $ingredient->delete();

        return response()->json(['message' => 'Ingrediente eliminado.']);
    }

    // ── Recetas ───────────────────────────────────────────────────────────────

    public function recipes(): JsonResponse
    {
        $recipes = Recipe::with('user:id,name')
            ->orderBy('status')
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['data' => $recipes]);
    }

    public function destroyRecipe(Recipe $recipe): JsonResponse
    {
        $recipe->delete();

        return response()->json(['message' => 'Receta eliminada.']);
    }

    // ── Usuarios ──────────────────────────────────────────────────────────────

    public function users(): JsonResponse
    {
        $users = User::orderBy('name')
            ->get(['id', 'name', 'email', 'is_admin', 'created_at']);

        return response()->json(['data' => $users]);
    }

    // ── Comentarios ───────────────────────────────────────────────────────────

    public function comments(): JsonResponse
    {
        $comments = Comment::with(['user:id,name', 'recipe:id,titulo'])
            ->latest()
            ->get();

        return response()->json(['data' => $comments]);
    }

    public function destroyComment(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json(['message' => 'Comentario eliminado.']);
    }
}
