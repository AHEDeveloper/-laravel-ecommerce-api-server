<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductGalleryController;
use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\CartApiController;
use App\Http\Controllers\Api\V1\CategoryApiController;
use App\Http\Controllers\Api\V1\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::apiResource('/products', ProductController::class);
        Route::apiResource('/categories', CategoryController::class);
        Route::apiResource('/users', UserController::class);
        Route::apiResource('/orders', OrderController::class);

        Route::prefix('product/{product}/gallery')->controller(ProductGalleryController::class)
            ->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::delete('/{image}', 'delete');
            });
    });
});

//////////////////User Api //////////////////////

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthApiController::class, 'register']);
    Route::post('/login', [AuthApiController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthApiController::class, 'logout']);
        Route::get('/carts',[CartApiController::class,'index']);
        Route::post('/carts',[CartApiController::class,'store']);

    });
    Route::get('/category',[CategoryApiController::class,'parent']);
    Route::get('/category/{id}/children',[CategoryApiController::class,'children']);

    Route::get('/products',[ProductApiController::class,'index']);
    Route::get('/products/{id}',[ProductApiController::class,'show']);

});


