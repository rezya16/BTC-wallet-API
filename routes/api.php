<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users', 'API\AuthController@register');
Route::post('/login','API\AuthController@login')->name('login');

Route::middleware('auth:api')->post('/wallets','WalletController@create');
Route::middleware('auth:api')->get('/wallets/{address}', 'WalletController@show');
Route::middleware('auth:api')->get('/wallets/{address}/transactions', 'TransactionController@searchByWallet');

Route::middleware('auth:api')->post('/transactions', 'TransactionController@create');
Route::middleware('auth:api')->get('/transactions', 'TransactionController@searchByUser');

