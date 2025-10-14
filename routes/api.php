<?php

use App\Http\Controllers\Api\V1\ProductsController;
use App\Http\Controllers\Api\V1\TagsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/products', [ProductsController::class, 'getList'])->name('products.getList');
    Route::get('/products/{id}', [ProductsController::class, 'getProduct'])->name('products.getProduct');

    Route::get('/tags/popular', [TagsController::class, 'getPopularList'])->name('tags.getPopularList');
});
