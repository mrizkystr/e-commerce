<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MerchantController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'index']);
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:api');


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/store', [ProductController::class, 'store']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

Route::prefix('ecommerce/merchants')->group(function () {
    Route::get('/', [MerchantController::class, 'index']);
    Route::post('/store', [MerchantController::class, 'store']);
    Route::get('/{id}', [MerchantController::class, 'show']);
    Route::put('/{id}', [MerchantController::class, 'update']);
    Route::delete('/{id}', [MerchantController::class, 'destroy']);
});
