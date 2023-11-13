<?php

use Illuminate\Support\Facades\Route;
Route::any('/', [\App\Http\Controllers\OrderController::class, 'index'])->middleware(['auth.shopify'])->name('home');
