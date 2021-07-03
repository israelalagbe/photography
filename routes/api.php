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
    Route::post('login', "AuthController@login");
    Route::post('register', "AuthController@register");

    Route::get('profile', "AuthController@getProfile")->middleware('jwt_auth');
});

Route::group(['prefix' => 'product_requests', 'middleware' => ['jwt_auth']], function () {
    Route::get('/', "ProductRequestController@getProductRequests");

    Route::get('/accepted', "ProductRequestController@getAcceptedProductRequests")
        ->middleware('role:photographer');

    Route::post('/:id/accept', "ProductRequestController@acceptProductRequest")
        ->middleware('role:photographer');

    Route::get('/client', "ProductRequestController@getClientProductRequests")
        ->middleware('role:client');

    Route::post('/', "ProductRequestController@storeProductRequest")
        ->middleware('role:client');
});
