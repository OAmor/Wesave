<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('account/activate/{token}', 'AuthController@accountActivate');


Route::get('account/password/find/{token}', 'PasswordResetController@find');
Route::post('account/password/reset', 'PasswordResetController@reset')->name('reset.password');
