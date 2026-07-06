<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::query()->with('category:id,name,slug')->latest()->get();
        return ApiResponseClass::apiResponse(true, 'get all products', $products, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $service = new ProductService();
        $validation = $service->validation($request);
        $this->returnValidationErrors($validation);
        $data = $request->only(['category_id', 'name', 'description', 'price', 'stock', 'thumbnail']);
        $data['slug'] = Str::slug($data['name'], '-', null);
        $this->uploadProductThumbnail($request);
        $product = Product::query()->create($data);
        return ApiResponseClass::apiResponse(true, 'created new product', $product, 200);
    }

    public function uploadProductThumbnail($request)
    {
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('products', 'public');
            $data['thumbnail'] = $path;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (!$product) {
            return ApiResponseClass::apiResponse(false, 'product Failed', null, 422);
        }
        return ApiResponseClass::apiResponse(false, 'get product', $product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $service = new ProductService();
        $validation = $service->validation($request);
        $this->returnValidationErrors($validation);
        $data = $request->only(['category_id', 'name', 'description', 'price', 'stock', 'thumbnail']);
        $data['slug'] = Str::slug($data['name'], '-', null);
        $this->updateProductThumbnail($request, $product);
        $product->update($data);
        return ApiResponseClass::apiResponse(true, 'update product successFully', $product, 200);
    }

    public function updateProductThumbnail($request, $product)
    {
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                return Storage::disk('public')->delete($product->thumbnail);
            }
            $path = $request->file('thumbnail')->store('products', 'public');
            $data['thumbnail'] = $path;
        }
    }

    public function returnValidationErrors($validation)
    {
        if ($validation->fails()) {
            return ApiResponseClass::apiResponse(false, 'Validation failed', $validation->errors(), 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (!$product) {
            return ApiResponseClass::apiResponse(false, 'product Failed', null, 422);
        }
        $product->delete();
        return ApiResponseClass::apiResponse(true, 'product deleted', null, 200);

    }
}
