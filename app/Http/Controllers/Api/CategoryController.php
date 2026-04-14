<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

class CategoryController extends Controller
{
    protected $model = Category::class;

    protected function buildIndexFetchQuery(Request $request, array $requestedRelations): Builder
    {
        return parent::buildIndexFetchQuery($request, $requestedRelations)
            ->where('status', 'approved');
    }

    protected function beforeStore(Request $request, Model $entity): void
    {
        $entity->status = 'pending';
    }
}
