<?php

/**
 * Categories
 */
Route::namespace('Categories')->group(function () {
    Route::apiResource('/categories', 'CategoryController');
});

/**
 * Products
 */
Route::namespace('Products')->group(function () {
    Route::apiResource('/products', 'ProductController');
});