<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::prefix('categories')->group(function () {
        Route::get('', [CategoriesController::class, 'all'])->name('admin.categories.all');
        Route::get('create',[CategoriesController::class,'create'])->name('admin.categories.create');
        Route::post('',[CategoriesController::class,'store'])->name('admin.categories.store');
        Route::delete('{category_id}/delete',[CategoriesController::class,'delete'])->name('admin.categories.delete');
        Route::get('{category_id}/edit',[CategoriesController::class,'edit'])->name('admin.categories.edit');
        Route::put('{category_id}/update',[CategoriesController::class,'update'])->name('admin.categories.update');
    });
    Route::prefix('products')->group(function () {
        Route::get('', [ProductController::class, 'all'])->name('admin.products.all');
        Route::get('create',[ProductController::class,'create'])->name('admin.products.create');
        Route::post('',[ProductController::class,'store'])->name('admin.products.store');
       Route::delete('{product_id}/delete',[ProductController::class,'delete'])->name('admin.products.delete');
        Route::get('{product_id}/download/demo',[ProductController::class,'downloadDemo'])->name('admin.products.download.demo');
        Route::get('{product_id}/download/source',[ProductController::class,'downloadSource'])->name('admin.products.download.source');

    });
});
