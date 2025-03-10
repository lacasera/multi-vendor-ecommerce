<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' =>'auth:sanctum' ], function () {
    Route::post('checkouts', [CheckoutController::class, 'store']);
    Route::get('checkouts', [CheckoutController::class, 'index']);
    Route::post('checkouts/verify', [CheckoutController::class, 'verify']);
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::get('products', [ProductController::class, 'index']);
