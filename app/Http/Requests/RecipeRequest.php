<?php

namespace App\Http\Requests;

use Orion\Http\Requests\Request;

class RecipeRequest extends Request
{
    private function isPublishing(): bool
    {
        return $this->input('status') === 'published';
    }

    public function storeRules(): array
    {
        $required = $this->isPublishing() ? 'required' : 'nullable';

        return [
            'status' => ['sometimes', 'in:draft,published'],
            'titulo' => [$required, 'string', 'max:150'],
            'pasos' => [$required, 'string', 'max:10000'],
            'time' => [$required, 'integer', 'min:1', 'max:1440'],
            'foto' => ['nullable', 'url', 'max:2048'],

            'category_ids' => [$this->isPublishing() ? 'required' : 'sometimes', 'array', 'min:1', 'max:20'],
            'category_ids.*' => ['integer', 'distinct', 'exists:categories,id'],

            'ingredients' => ['sometimes', 'array', 'max:100'],
            'ingredients.*.id' => ['required', 'integer', 'distinct', 'exists:ingredients,id'],
        ];
    }

    public function updateRules(): array
    {
        return [
            'status' => ['sometimes', 'in:draft,published'],
            'titulo' => ['sometimes', $this->isPublishing() ? 'required' : 'nullable', 'string', 'max:150'],
            'pasos' => ['sometimes', $this->isPublishing() ? 'required' : 'nullable', 'string', 'max:10000'],
            'time' => ['sometimes', $this->isPublishing() ? 'required' : 'nullable', 'integer', 'min:1', 'max:1440'],
            'foto' => ['sometimes', 'nullable', 'url', 'max:2048'],

            'category_ids' => ['sometimes', 'array', 'max:20'],
            'category_ids.*' => ['integer', 'distinct', 'exists:categories,id'],

            'ingredients' => ['sometimes', 'array', 'max:100'],
            'ingredients.*.id' => ['required', 'integer', 'distinct', 'exists:ingredients,id'],
        ];
    }
}
