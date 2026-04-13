<?php

namespace App\Http\Controllers\Api;

use App\Models\Ingredient;
use Orion\Http\Controllers\Controller;

class IngredientController extends Controller
{
    protected $model = Ingredient::class;
}
