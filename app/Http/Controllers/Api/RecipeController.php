<?php

namespace App\Http\Controllers\Api;


use App\Models\Recipe;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class RecipeController extends Controller
{
    protected $model = Recipe::class;
    public function validationRules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'foto'   => ['nullable', 'string', 'max:255'],
            'pasos'  => ['required', 'string'],
        ];
    }
     protected function beforeStore(Request $request, $recipe) { 
        $recipe ->user_id = $request->user()->id;
    }
}
