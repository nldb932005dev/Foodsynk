<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    public function index(Recipe $recipe): JsonResponse
    {
        $comments = $recipe->comments()
            ->with('user:id,name')
            ->latest()
            ->get();

        return response()->json(['data' => $comments]);
    }

    public function store(Request $request, Recipe $recipe): JsonResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $comment = $recipe->comments()->create([
            'body'    => $validated['body'],
            'user_id' => $request->user()->id,
        ]);

        $comment->load('user:id,name');

        return response()->json(['data' => $comment], 201);
    }

    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['message' => 'Comentario eliminado.']);
    }
}
