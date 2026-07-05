<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()->latest()->get();
        return ApiResponseClass::apiResponse(true, 'get list categories', $categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $service = new CategoryService();
        $validation = $service->validation($request);
        if ($validation->fails())
        {
            return ApiResponseClass::apiResponse(false,'validation is error',$validation->errors(),422);
        }

        $category = Category::query()->create([
            'name' => $request->name,
            'slug' => $request->slug
        ]);
        return ApiResponseClass::apiResponse(true,'created category successfully',$category,200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        if (!$category)
        {
           return ApiResponseClass::apiResponse(false,'category is false',null,422);
        }
        return ApiResponseClass::apiResponse(true,'get category',$category,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category)
    {
        $service = new CategoryService();
        $validation = $service->validation($request);
        if ($validation->fails())
        {
            return ApiResponseClass::apiResponse(false,'validation is error',$validation->errors(),422);
        }
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug)
        ]);
        return ApiResponseClass::apiResponse(true,'update Category',$category,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (!$category)
        {
            return ApiResponseClass::apiResponse(false,'category is false',null,422);
        }
        $category->delete();
        return ApiResponseClass::apiResponse(true,'delete category successfully',null,200);
    }
}
