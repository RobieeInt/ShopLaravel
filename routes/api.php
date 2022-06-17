<?php

use App\Http\Controllers\API\LikeProductController;
use App\Http\Controllers\API\ProductCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TransactionController;

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


// Daftarin route API
Route::get('products', [ProductController::class, 'all']);
Route::get('product-categories', [ProductCategoryController::class, 'all']);


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfil']);
    Route::post('logout', [UserController::class, 'logout']);

    // Route Transaksi
    Route::get('transactions', [TransactionController::class, 'all']);
    Route::post('transactions', [TransactionController::class, 'checkout']);

    // Route Productlike
    Route::get('product-likes', [LikeProductController::class, 'all']);
    Route::get('product-like', [LikeProductController::class, 'getlike']);
    Route::post('product-likes', [LikeProductController::class, 'like']);
});
