<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Model;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;
use App\Policies\RecipePolicy;

class RecipeController extends Controller
{
    protected $model = Recipe::class;
    protected $policy = RecipePolicy::class;

    public function searchableBy(): array
    {
        return ['titulo', 'pasos'];
    }

    protected function beforeStore(Request $request, Model $entity)
    {
        $entity->user_id = $request->user()->id;
    }

    protected function beforeSave(Request $request, Model $entity)
    {
        if (!$entity->exists) {
            $entity->user_id = $request->user()->id;
        }
    }

    protected function afterSave(Request $request, Model $entity)
    {
        $validated = $request->validated();

        if (array_key_exists('category_ids', $validated)) {
            $entity->categories()->sync($validated['category_ids']);
        }
        if (array_key_exists('ingredients', $validated)) {
            $ingredientIds = collect($validated['ingredients'])
                ->pluck('id')
                ->all();

            $entity->ingredients()->sync($ingredientIds);
        }

    }


}
