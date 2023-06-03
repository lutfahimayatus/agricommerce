<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Shipping\ShippingCostController;
use App\Http\Controllers\Transaction\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('guest')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::post('/{product}/cart', [CartController::class, 'add']);
    });

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::patch('/{product}', [CartController::class, 'update']);
        Route::post('/checkout', [CartController::class, 'checkout']);
    });

    Route::get('shipping-costs', [ShippingCostController::class, 'index']);

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/{transaction}', [TransactionController::class, 'submit']);
    });
});
