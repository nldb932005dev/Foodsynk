<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'instructions' => ['required', 'string', 'max:10000'],
            'time' => ['required', 'integer', 'min:1', 'max:1440'],
            'photo' => ['nullable', 'url', 'max:2048'],

            'category_ids' => ['sometimes', 'array', 'max:20'],
            'category_ids.*' => ['integer', 'distinct', 'exists:categories,id'],

            'ingredients' => ['sometimes', 'array', 'max:100'],
            'ingredients.*.id' => ['required', 'integer', 'distinct', 'exists:ingredients,id'],
            'ingredients.*.amount' => ['nullable', 'numeric', 'gt:0', 'max:99999.99'],
            'ingredients.*.unit' => ['nullable', 'string', 'max:30'],
        ];
    }
}
