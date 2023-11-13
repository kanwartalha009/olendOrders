<?php

use Illuminate\Support\Facades\Route;
Route::any('/', [\App\Http\Controllers\OrderController::class, 'index'])->middleware(['auth.shopify'])->name('home');
Route::any('/sync/orders', [\App\Http\Controllers\OrderController::class, 'ordersSync'])->name('order.sync');
