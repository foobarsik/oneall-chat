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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(
	[
		'prefix' => 'v1',
	    'namespace' => 'Api\V1'
    ],
    function () {
        Route::resource('bots', 'BotController', ['except' => ['create', 'edit', 'update']]);
        Route::post('/viber/callback/{token}', 'ViberController@handleCallback');
        Route::post('/telegram/callback/{token}', 'TelegramController@handleCallback');
});
