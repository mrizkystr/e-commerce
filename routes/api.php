<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\UpdateTest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\DetailProductController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;

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
Route::get('auth/redirect/{provider}', [SocialiteController::class, 'redirectToProvider']);
Route::get('auth/callback/{provider}', [SocialiteController::class, 'handleProviderCallback']);

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/store', [ProductController::class, 'store']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

Route::prefix('merchants')->group(function () {
    Route::get('/', [MerchantController::class, 'index']);
    Route::post('/store', [MerchantController::class, 'store']);
    Route::get('/{id}', [MerchantController::class, 'show']);
    Route::put('/{id}', [MerchantController::class, 'update']);
    Route::delete('/{id}', [MerchantController::class, 'destroy']);
});

Route::prefix('banners')->group(function () {
    Route::get('/', [BannerController::class, 'index']);
    Route::get('/{id}', [BannerController::class, 'show']);
    Route::post('/store', [BannerController::class, 'store']);
    Route::put('/{id}', [BannerController::class, 'update']);
    Route::delete('/{id}', [BannerController::class, 'destroy']);
});


Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::post('/store', [OrderController::class, 'store']);
    Route::put('/{id}', [OrderController::class, 'update']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
    Route::post('/{id}/payment', [OrderController::class, 'createPayment']); // Route untuk membuat pembayaran
    Route::post('/midtrans/notification', [OrderController::class, 'handleNotification']); // Route untuk notifikasi Midtrans
});

Route::prefix('carts')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::get('/{id}', [CartController::class, 'show']);
    Route::post('/store', [CartController::class, 'store']);
    Route::put('/{id}', [CartController::class, 'update']);
    Route::delete('/{id}', [CartController::class, 'destroy']);
});

Route::prefix('/carts-items')->group(function () {
    Route::get('/', [CartItemController::class, 'index']);
    Route::post('/store', [CartItemController::class, 'store']);
    Route::get('/{id}', [CartItemController::class, 'show']);
    Route::put('/{id}', [CartItemController::class, 'update']);
    Route::delete('/{id}', [CartItemController::class, 'destroy']);
});

Route::prefix('wishlists')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('wishlists.index');
    Route::post('/store', [WishlistController::class, 'store'])->name('wishlists.store');
    Route::get('/{id}', [WishlistController::class, 'show'])->name('wishlists.show');
    Route::put('/{id}', [WishlistController::class, 'update'])->name('wishlists.update');
    Route::delete('/{id}', [WishlistController::class, 'destroy'])->name('wishlists.destroy');
});

Route::prefix('profiles')->middleware('auth:api')->group(function () {
    Route::get('/{id}', [ProfileController::class, 'show'])->name('profiles.show');
    Route::post('/store', [ProfileController::class, 'store'])->name('profiles.store');
    Route::delete('/delete', [ProfileController::class, 'destroy'])->name('profiles.destroy');
    Route::post('/update/{id}', [ProfileController::class, 'update'])->name('profiles.update');
});

Route::prefix('detail')->group(function () {
    Route::get('/product', [DetailProductController::class, 'index']);
    Route::get('/product/{id}', [DetailProductController::class, 'show']);
    Route::post('/product', [DetailProductController::class, 'store']);
    Route::put('/product/{id}', [DetailProductController::class, 'update']);
    Route::delete('/product/{id}', [DetailProductController::class, 'destroy']);
});

Route::prefix('shipments')->group(function () {
    // Location endpoints
    Route::get('/location/provinces', [ShipmentController::class, 'getProvinces']);
    Route::get('/location/regencies/{provinceId}', [ShipmentController::class, 'getRegencies']);
    Route::get('/location/districts/{regencyId}', [ShipmentController::class, 'getDistricts']);
    Route::get('/location/villages/{districtId}', [ShipmentController::class, 'getVillages']);
    
    // Shipment endpoints
    Route::get('/', [ShipmentController::class, 'index']);
    Route::get('/{id}', [ShipmentController::class, 'show']);
    Route::post('/store', [ShipmentController::class, 'store']);
    Route::put('/{id}', [ShipmentController::class, 'update']);
    Route::delete('/{id}', [ShipmentController::class, 'destroy']);
});
