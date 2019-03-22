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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('auth/login', 'UserController@authenticate');
Route::post('auth/register', 'UserController@register');

Route::put('short/url', 'ShortUrlController@shortUrl');

Route::get('top/{top}/most/visited/urls', 'ShortUrlStatsController@getTopVisitedShortUrls');
