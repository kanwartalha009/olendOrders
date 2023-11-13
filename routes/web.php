<?php

use Illuminate\Support\Facades\Route;
Route::any('/', [\App\Http\Controllers\ProductController::class, 'allProducts'])->middleware(['verify.shopify'])->name('home');
Route::get('/country', [\App\Http\Controllers\ProductController::class, 'countryCode'])->middleware(['verify.shopify'])->name('country.all');
Route::any('/return', [\App\Http\Controllers\ProductController::class, 'returnRequest'])->middleware(['verify.shopify'])->name('return.all');
Route::any('/return/save', [\App\Http\Controllers\ProductController::class, 'returnRequestSave'])->name('return.save');
Route::any('/return/{id}/delete', [\App\Http\Controllers\ProductController::class, 'returnRequestDelete'])->name('return.delete');
Route::any('/products/sync', [\App\Http\Controllers\ProductController::class, 'syncPricing'])->name('pricing.sync');
Route::any('/products/all', [\App\Http\Controllers\ProductController::class, 'shopifyProducts'])->name('products.all');
Route::any('/products/all/sync', [\App\Http\Controllers\ProductController::class, 'shopifyProductsSync'])->name('products.sync');
Route::any('/country/save', [\App\Http\Controllers\ProductController::class, 'countryCodeSave'])->name('countryCode.save');
Route::any('/country/{id}/update', [\App\Http\Controllers\ProductController::class, 'countryCodeUpdate'])->name('countryCode.update');
Route::any('/country/{id}/delete', [\App\Http\Controllers\ProductController::class, 'countryCodeDelete'])->name('countryCode.delete');

Route::any('/feed/settings', [\App\Http\Controllers\ProductController::class, 'feedSettings'])->name('feed.settings');

Route::get('/products/export', [\App\Http\Controllers\ProductController::class, 'export'])->name('product.export');
Route::get('/products/xml', [\App\Http\Controllers\ProductController::class, 'exportXML'])->name('xml.export');
Route::get('/productsTest/xml', [\App\Http\Controllers\ProductController::class, 'exportTestXML'])->name('xml.test.export');
Route::get('/mainproducts/xml', [\App\Http\Controllers\ProductController::class, 'exportMainXML'])->name('xml.main.export');
