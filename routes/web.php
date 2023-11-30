<?php

use Illuminate\Support\Facades\Route;
Route::any('/', [\App\Http\Controllers\OrderController::class, 'index'])->middleware(['auth.shopify'])->name('home');
Route::any('/sync/orders', [\App\Http\Controllers\OrderController::class, 'ordersSync'])->name('order.sync');
Route::any('/csv', [\App\Http\Controllers\OrderController::class, 'csvExport'])->name('order.csv');
Route::any('/english', function (){
    return view('test');
});
Route::any('/english/get', [\App\Http\Controllers\OrderController::class, 'testSave'])->name('test.save');
