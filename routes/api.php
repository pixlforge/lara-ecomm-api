<?php

/**
 * Categories
 */
Route::namespace('Categories')->group(function () {
    Route::apiResource('/categories', 'CategoryController');
});