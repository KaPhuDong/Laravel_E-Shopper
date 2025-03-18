<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\TaoBang;
use  App\Http\Controllers\PageController;
use  App\Http\Controllers\PageAdminController;
Route::get('/', [PageController::class, 'getIndex'])->name('trangchu.index');
Route::get('a', [TaoBang::class,'createTable']);
Route::prefix('index')->group(function () {
    Route::get('/', [PageController::class, 'getIndex'])->name('trangchu.index');
    Route::get('product', [PageController::class, 'getProduct'])->name('trangchu.product');
    Route::get('type_product/{id}', [PageController::class, 'getTypeProduct'])->name('trangchu.type_product');
    Route::get('detail/{id}', [PageController::class,'getDetail']);
});
// Route::get('detail/{id}', [PageController::class,'getDetail']);
Route::post('comment/{id}', [PageController::class,'newComment']);
Route::get('about', [PageController::class,'getAbout']);
Route::get('contact', [PageController::class, 'getContact']);
Route::post('contact', [PageController::class, 'postContact']);
Route::get('admin', [PageAdminController::class,'index']);
Route::get('addProduct', [PageAdminController::class,'create']);
Route::post('storeProduct', [PageAdminController::class,'store']);
Route::get('editProduct/{id}', [PageAdminController::class,'edit']);
Route::post('updateProduct', [PageAdminController::class,'update']);
Route::post('deleteProduct/{id}', [PageAdminController::class,'destroy']);
