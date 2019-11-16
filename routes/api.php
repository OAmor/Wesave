<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');
        Route::post('forgot', 'PasswordResetController@create');

        Route::group(['middleware' => 'auth:api'], function() {
            Route::get('logout', 'AuthController@logout');
            Route::get('user', 'AuthController@user');
        });
    });

    Route::group(['prefix' => 'qr','middleware' => 'auth:api'], function() {
        //Scanner Actions
        Route::post('store', 'ScannerController@store');
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('recipes', 'ScannerController@getRecipes');
    });
    Route::get('test', 'ScannerController@test');

    Route::group(['prefix' => 'products', 'middleware' => 'auth:api'], function(){
        Route::post('{id}/update', 'ProductController@update');
        Route::get('all', 'productController@all');
        Route::post('{id}/delete', 'ProductController@delete');
    });
});



