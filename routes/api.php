<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/products', [ProductController::class, 'index'])->middleware('throttle:5000,5');
Route::get('/products/create-many', [ProductController::class, 'createByChunk']);
Route::get('/products/clear', [ProductController::class, 'clear']);
