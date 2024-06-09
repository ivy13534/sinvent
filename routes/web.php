<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BarangController;

// Define the route for the '/' URI outside of any callback function
Route::get('/', function () {
    return view('welcome');
});

// Define the resourceful route for '/products'
Route::resource('products', ProductController::class);
Route::resource('category', CategoryController::class);
Route::resource('barang', BarangController::class);