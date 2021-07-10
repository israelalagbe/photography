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

    Route::group(['middleware' => 'role:photographer'], function () {
        Route::get('/accepted', "ProductRequestController@getAcceptedProductRequests");

        Route::post('/accept', "ProductRequestController@acceptProductRequest");

        Route::post('{id}/submissions', "ProductSubmissionController@submitProduct");
    });

    Route::group(['middleware' => 'role:client'], function () {
        Route::get('/client', "ProductRequestController@getClientProductRequests");

        Route::post('/', "ProductRequestController@storeProductRequest");

        Route::get('{id}/submissions', "ProductSubmissionController@getProductSubmissions");

        Route::post('submissions/{id}/approve', "ProductSubmissionController@approveProductSubmission");

        Route::post('submissions/{id}/decline', "ProductSubmissionController@declineProductSubmission");
    });
});
