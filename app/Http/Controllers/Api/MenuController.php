<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class MenuController extends Controller
{
    protected $model = Menu::class;

    protected function buildIndexFetchQuery(Request $request, array $requestedRelations): Builder
    {
        return parent::buildIndexFetchQuery($request, $requestedRelations)
            ->where('user_id', $request->user()->id);
    }

    protected function buildSearchFetchQuery(Request $request, array $requestedRelations): Builder
    {
        return parent::buildSearchFetchQuery($request, $requestedRelations)
            ->where('user_id', $request->user()->id);
    }

    protected function beforeStore(Request $request, Model $entity): void
    {
        $entity->user_id = $request->user()->id;
    }

    protected function afterStore(Request $request, Model $entity): void
    {
        if ($request->has('recipe_ids')) {
            $entity->recipes()->sync($request->input('recipe_ids', []));
        }
    }

    protected function afterUpdate(Request $request, Model $entity): void
    {
        if ($request->has('recipe_ids')) {
            $entity->recipes()->sync($request->input('recipe_ids', []));
        }
    }

    public function shoppingList(Request $request, Menu $menu): JsonResponse
    {
        $this->authorize('view', $menu);

        $menu->load('recipes.ingredients');

        $ingredients = $menu->recipes
            ->flatMap(fn($recipe) => $recipe->ingredients)
            ->unique('id')
            ->values();

        return response()->json(['data' => $ingredients]);
    }
}
