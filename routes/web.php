<?php

use Illuminate\Support\Facades\Route;
Route::any('/', [\App\Http\Controllers\OrderController::class, 'allProducts'])->middleware(['verify.shopify'])->name('home');
