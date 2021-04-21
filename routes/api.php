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

Route::prefix('v1')->group(function() {
	Route::post('auth/register', 'UserController@register');
	Route::post('auth/login', 'UserController@login');
	
	Route::middleware('auth:api')->group(function() {
		Route::get('auth/logout', 'UserController@logout');

		Route::apiResource('board', 'BoardController');

		Route::prefix('board/{board}')->group(function() {
			Route::post('member', 'BoardController@storeMember');
			Route::delete('member/{member}', 'BoardController@destroyMember');

			Route::apiResource('list', 'BoardListController');
			Route::prefix('list/{list}')->group(function() {
				Route::post('right', 'BoardListController@right');
				Route::post('left', 'BoardListController@left');

				Route::apiResource('card', 'CardController');
				Route::post('card/{card}/down', 'CardController@down');
				Route::post('card/{card}/up', 'CardController@up');
			});
		});

		Route::post('card/{card}/move/{list}', 'CardController@moveList');
	});
});