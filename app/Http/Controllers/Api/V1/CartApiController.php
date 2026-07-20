<?php

namespace App\Http\Controllers\Api\V1;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartApiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $items = CartItem::query()
            ->with('product:id,name,slug,thumbnail')
            ->get(['id','product_id','quantity']);

        $totalQty = 0;
        $totalPrice = 0;
        $payloadItems = $items->map(function ($item) use ($totalQty,$totalPrice){
           $product = $item->product;
           $lineTotal = $item->quantity * $product->price;
           $totalQty += $item->quantity;
           $totalPrice += $product->price;
           return [
               'id' => $item->id,
               'product' => $product ? [
                   'id' => $product->id,
                   'name' => $product->name,
                   'slug' => $product->slug,
               ]:null,
               'quantity' => $totalQty,
               'total_line' => $totalPrice
           ];
        });
        return ApiResponseClass::apiResponse(true,'ok',[
            'items' => $payloadItems,
            'totalQty' => $totalQty,
            'totalPrice' => $totalPrice
        ],200);
    }
}
