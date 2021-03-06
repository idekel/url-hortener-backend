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


Route::get('/g/{hash}', 'ShortUrlRedirectController@goTo')->name('hash');

Route::get('/migrate', function () {
    echo \Illuminate\Support\Facades\Artisan::call('migrate');
});

//Route::get('/phpinfo', function (){
//    phpinfo();
//});
