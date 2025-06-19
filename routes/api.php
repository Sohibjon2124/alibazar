<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BasketController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\PromotionProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->middleware('api')->group(function ($router) {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::post('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::post('/', [CategoryController::class, 'store']);

            Route::prefix('{category}')->group(function () {
                Route::put('/', [CategoryController::class, 'update']);
                Route::get('/products', [CategoryController::class, 'products']);
            });
        });

        Route::prefix('products')->group(function () {

            Route::post('/', [ProductController::class, 'store']);
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/search', [ProductController::class, 'search']);


            Route::prefix('promotion')->group(function () {
                Route::get('/', [PromotionProductController::class, 'index']);
            });

            Route::prefix('{product}')->group(function () {

                Route::get('/', [ProductController::class, 'show']);
                Route::put('/', [ProductController::class, 'update']);

                Route::prefix('promotion')->group(function () {
                    Route::post('/', [PromotionProductController::class, 'store']);
                    Route::put('/', [PromotionProductController::class, 'update']);
                });
            });
        });

        Route::prefix('baskets')->group(function () {
            Route::get('/', [BasketController::class, 'index']);
            Route::post('/', [BasketController::class, 'store']);

            Route::prefix('{basket}')->group(function () {
                Route::put('/', [BasketController::class, 'update']);
                Route::delete('/', [BasketController::class, 'delete']);
            });
        });

        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::post('/', [OrderController::class, 'store']);

            Route::prefix('{order}')->group(function () {
                Route::put('/', [OrderController::class, 'update']);
                Route::delete('/', [OrderController::class, 'delete']);
            });
        });
    });
});
