<?php

namespace App\Http\Controllers\Api\V1;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    public function parent()
    {
        $parents = Category::query()->select(['id', 'name', 'slug', 'parent_id'])
            ->whereNull('parent_id')->get();
        return ApiResponseClass::apiResponse(true, 'get all parents', $parents, 200);
    }

    public function children($id)
    {
        $parent = Category::query()->select(['name', 'slug', 'parent_id'])->find($id);
        if (!$parent)
        {
            return ApiResponseClass::errorResponse('not Found', 'parent is not', null, 404);
        }
        $children = Category::query()->where('parent_id', $parent->id)->get();
        return ApiResponseClass::apiResponse(true, 'get all children',[
            'items' => $children,
            'parent' => $parent
        ], 200);

    }
}
