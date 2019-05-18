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

/*
|--------------------------------------------------------------------------
| SessionController routes
|--------------------------------------------------------------------------
|
| Those routes must be used by local API clients, see SessionController.
|
*/
Route::post('/token', 'SessionController@createToken')
    ->name('api.session.token.create');

Route::post('/token/refresh', 'SessionController@refreshToken')
    ->name('api.session.token.refresh');

Route::middleware('auth:api')
    ->delete('/token', 'SessionController@destroyToken')
    ->name('api.session.token.destroy');

Route::middleware('auth:api')
    ->get('/user', 'SessionController@getUser')
    ->name('api.session.user');
