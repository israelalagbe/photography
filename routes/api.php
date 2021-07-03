<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductRequestController;
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


Route::group(['prefix' => 'auth',], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::get('profile', [AuthController::class, 'getProfile'])->middleware('jwt_auth');
});

Route::group(['prefix' => 'product_requests', 'middleware' => ['jwt_auth']], function () {
    Route::get('/', [ProductRequestController::class, 'getProductRequests']);
    Route::get('/accepted', [ProductRequestController::class, 'getAcceptedProductRequests']);
    Route::get('/users/{user_id}', [ProductRequestController::class, 'getClientProductRequests']);

    Route::post('/', [ProductRequestController::class, 'storeProductRequest']);
});
