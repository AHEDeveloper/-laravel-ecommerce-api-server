<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductGalleryController extends Controller
{
    public function index(Product $product)
    {
        return ApiResponseClass::apiResponse(
            true,
            'product gallery retrived successfully',
            $product->gallery()->get(),
            200);
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:png,jpg,webp,jpeg,svg,gif|max:2048'
        ]);
        $savedImages = [];
        $this->saveProductGallery($request,$product);
        return ApiResponseClass::apiResponse(
            true,
            'image saved successfully',
            $savedImages,
            201);
    }

    public function saveProductGallery($request,$product)
    {
        foreach ($request->file('images') as $image) {
            $path = $image->store("products/" . $product->id . "/gallery", 'public');
            $savedImages = $product->gallery()->create([
                'path' => $path
            ]);
        }
    }

    public function delete(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return ApiResponseClass::errorResponse(false,
                'this image dose blonde to product',
                null, 403);
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();
        return ApiResponseClass::apiResponse(true,
            'image deleted',
            null, 200);
    }
}
