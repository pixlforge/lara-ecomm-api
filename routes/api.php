<?php

use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Addresses\AddressController;
use App\Http\Controllers\Countries\CountryController;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Addresses\AddressShippingController;
use App\Http\Controllers\Orders\OrderController;

/**
 * Addresses
 */
Route::get('/addresses/{address}/shipping', AddressShippingController::class)->name('addresses.shipping');
Route::apiResource('/addresses', AddressController::class);

/**
 * Auth
 */
Route::prefix('/auth')->group(function () {
    Route::post('/register', RegisterController::class)->name('auth.register');
    Route::post('/login', LoginController::class)->name('auth.login');
    Route::get('/me', MeController::class)->name('auth.me');
});

/**
 * Cart
 */
Route::apiResource('/cart', CartController::class, [
    'parameters' => [
        'cart' => 'productVariation'
    ]
]);

/**
 * Categories
 */
Route::apiResource('/categories', CategoryController::class);

/**
 * Countries
 */
Route::apiResource('/countries', CountryController::class);

/**
 * Orders
 */
Route::apiResource('/orders', OrderController::class);

/**
 * Products
 */
Route::apiResource('/products', ProductController::class);
