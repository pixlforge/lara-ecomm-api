<?php

/**
 * Auth
 */
Route::prefix('/auth')->namespace('Auth')->group(function () {
    Route::post('/register', 'RegisterController')->name('auth.register');
    Route::post('/login', 'LoginController')->name('auth.login');
    Route::get('/me', 'MeController')->name('auth.me');
});

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