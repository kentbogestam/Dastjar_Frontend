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

// Route::group(['prefix' => 'dast-jar'], function() {
// 	Route::get('/', ['uses' => 'HomeController@index']);

// });

// Route::get('/', function () {
//     return view('index');
// });

	Auth::routes();

	Route::get('/home', 'HomeController@index')->name('home');

	Route::get('/login/{social}','Auth\LoginController@socialLogin')->where('social','facebook|google');
	Route::get('/login/{social}/callback','Auth\LoginController@handelProviderCallback')->where('social','facebook|google');

	Route::get('/', 'HomeController@index');
	Route::get('eat-later', 'HomeController@eatLater');
	Route::get('eat-now', 'HomeController@index');
	Route::get('restro-menu-list/{companyId}', 'HomeController@menuList');
	Route::get('search-map-eatnow', 'MapController@searchMapEatnow');
	Route::get('search-map-eatlater', 'MapController@searchMapEatlater');
	Route::post('save-order', 'OrderController@saveOrder');




