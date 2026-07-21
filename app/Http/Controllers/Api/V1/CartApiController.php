<?php

namespace App\Http\Controllers\Api\V1;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartApiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $items = CartItem::query()
            ->with('product:id,name,slug,price,thumbnail')
            ->get(['id', 'product_id', 'quantity']);

        $totalQty = 0;
        $totalPrice = 0;
//        dd($items);
        $payloadItems = $items->map(function ($item) use (&$totalQty, &$totalPrice) {
            $product = $item->product;
            $lineTotal = $item->quantity * $product->price;
            $totalQty += $item->quantity;
            $totalPrice += $lineTotal;
            return [
                'id' => $item->id,
                'product' => $product ? [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                ] : null,
                'quantity' => $totalQty,
                'total_line' => $lineTotal
            ];
        });
        return ApiResponseClass::apiResponse(true, 'ok', [
            'items' => $payloadItems,
            'totalQty' => $totalQty,
            'totalPrice' => $totalPrice
        ], 200);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'product_id' => 'required|integer|min:1|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);
        if ($validation->fails()) {
            return ApiResponseClass::apiResponse(false, 'validation fails', $validation->errors(), 422);
        }

        $userId = Auth::id();
        $productId = $request->product_id;
        $qty = $request->quantity ?? 1;
        $product = Product::query()->select(['id', 'stock'])->find($productId);
        if (!$product) {
            return ApiResponseClass::apiResponse(false, 'not_found product', null, 422);
        }
        if ($qty < 1) $qty = 1;
        if ($qty > 100) $qty = 100;

        DB::beginTransaction();

        try {
            $item = CartItem::query()
                ->where('product_id', $productId)
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            if ($item) {
                    $newQty = $item->quantity + $qty;
                    if ($newQty > $product->stock)
                    {
                        DB::rollBack();
                        return ApiResponseClass::apiResponse(false,'product Out of sock',null,422);
                    }
                    $item->quantity = $newQty;
                    $item->save();
            }else{
                if ($qty > $product->stock){
                    DB::rollBack();
                    return ApiResponseClass::apiResponse(false,'product Out of sock',null,422);
                }
                $item = CartItem::query()->create([
                   'product_id' => $productId,
                   'quantity' => $qty,
                   'user_id' => $userId
                ]);
            }
             DB::commit();
            return ApiResponseClass::apiResponse(true,'Add to cart',[
                'id' => $item->id,
                'product_id' => $product->id,
                'quantity' => $item->quantity
            ],201);

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponseClass::apiResponse(false,'cloud not to add cart',$e->getMessage(),500);

        }
    }

    public function destroy($cartId)
    {
        $cart = CartItem::query()
            ->where('id',$cartId)
            ->where('user_id',Auth::id())
            ->first();
        if (!$cart)
        {
            return ApiResponseClass::apiResponse(false,'cart not_found',null,404);
        }
        $cart->delete();
        return ApiResponseClass::apiResponse(true,'cart item deleted',null,200);
    }
    public function clear()
    {
        $cart = CartItem::query()
            ->where('user_id',Auth::id())
           ->delete();

        return ApiResponseClass::apiResponse(true,'cart item cleared',null,200);
    }
}
