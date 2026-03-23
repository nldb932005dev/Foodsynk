<?php

namespace App\Http\Requests;

use Orion\Http\Requests\Request;

class RecipeRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:150'],
            'pasos' => ['required', 'string', 'max:10000'],
            'time' => ['required', 'integer', 'min:1', 'max:1440'],
            'foto' => ['nullable', 'url', 'max:2048'],

            'category_ids' => ['sometimes', 'array', 'max:20'],
            'category_ids.*' => ['integer', 'distinct', 'exists:categories,id'],

            'ingredients' => ['sometimes', 'array', 'max:100'],
            'ingredients.*.id' => ['required', 'integer', 'distinct', 'exists:ingredients,id'],
        ];
    }

    public function updateRules(): array
    {
        return [
            'titulo' => ['sometimes', 'required', 'string', 'max:150'],
            'pasos' => ['sometimes', 'required', 'string', 'max:10000'],
            'time' => ['sometimes', 'required', 'integer', 'min:1', 'max:1440'],
            'foto' => ['sometimes', 'nullable', 'url', 'max:2048'],

            'category_ids' => ['sometimes', 'array', 'max:20'],
            'category_ids.*' => ['integer', 'distinct', 'exists:categories,id'],

            'ingredients' => ['sometimes', 'array', 'max:100'],
            'ingredients.*.id' => ['required', 'integer', 'distinct', 'exists:ingredients,id'],
        ];
    }
}
