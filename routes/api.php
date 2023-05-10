<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [LoginController::class,'register'])->name('register');
Route::post('login', [LoginController::class,'login'])->name('login');

//routes orders
Route::middleware('auth:api')->post('orders', [OrderController::class,'store'])->name('store');
Route::middleware('auth:api')->get('orders', [OrderController::class,'index'])->name('index');
Route::middleware('auth:api')->get('orders/{id}', [OrderController::class,'show'])->name('show');
Route::middleware('auth:api')->delete('orders/{id}', [OrderController::class,'destroy'])->name('destroy');
Route::middleware('auth:api')->put('orders/{id}', [OrderController::class,'update'])->name('update');

//routes products
Route::middleware('auth:api')->get('products', [ProductController::class,'index'])->name('index');
Route::middleware('auth:api')->get('products/{id}', [ProductController::class,'show'])->name('show');
Route::middleware('auth:api')->post('products', [ProductController::class,'store'])->name('store');

