<?php

namespace App\Http\Controllers\Api\V1;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->select('id', 'name', 'price', 'stock', 'description', 'thumbnail')
            ->with('category:id,name,slug');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->get('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->get('max_price'));
        }
        switch ($request->get('sort')) {
            case 'price_asc';
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc';
                $query->orderBy('price', 'desc');
                break;
            case 'latest';
            default:
                $query->orderBy('id', 'desc');
                break;

        }

        $per_page = (int) $request->get('per_page',10);
        if ($per_page < 1) $per_page = 10;
        if ($per_page > 100) $per_page = 100;

        $page = $query->paginate($per_page);
        $items = collect($page->items())->map(function ($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'price' => $item->price,
                'stock' => $item->stock,
                'thumbnail' => $item->thumbnail,
                'category' => [
                    'id' => $item->category?->id,
                    'name' => $item->category?->name,
                ]
            ];
        });
        return ApiResponseClass::apiResponse(true,'ok',[
            'items' => $items,
            'meta' => [
                'total' => $page->total(),
                'current_page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'last_page' => $page->lastPage(),
            ]
        ],200);
    }

    public function show(Request $request,$id)
    {
        $product = Product::query()->find($id);
        if (!$product) return ApiResponseClass::apiResponse(false,'not_found',null,404);

        $data = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->price,
            'stock' => $product->stock,
            'thumbnail' => $product->thumbnail,
            'category' => [
                'id' => $product->category?->id,
                'name' => $product->category?->name,
            ],
            'images' => $product->gallery->map(fn($img) => $img->path)->values()
        ];
        return ApiResponseClass::apiResponse(true,'ok',$data,200);
    }
}
