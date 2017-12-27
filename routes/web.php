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
	Route::post('eat-later', 'HomeController@eatLater');
	Route::get('eat-later', 'HomeController@eatLater');
	Route::get('selectOrder-date', 'HomeController@selectOrderDate');
	Route::get('eat-later-map', 'HomeController@eatLaterMap');
	Route::get('eat-now', 'HomeController@index');
	Route::get('restro-menu-list/{storeID}', 'HomeController@menuList');
	Route::get('search-map-eatnow', 'MapController@searchMapEatnow');
	Route::get('search-store-map', 'MapController@searchStoreMap');
	Route::get('search-map-eatlater', 'MapController@searchMapEatlater');
	Route::post('save-order', 'OrderController@saveOrder');
	Route::get('order-view/{OrderId}', 'OrderController@orderView');
	Route::get('lat-long', 'HomeController@userLatLong');
	Route::get('user-setting', 'CustomerController@index');
	Route::post('save-setting', 'CustomerController@saveSetting');
	Route::resource('customer', 'CustomerController');
	Route::get('select-location', 'CustomerController@selectLocation');
	Route::post('save-location', 'CustomerController@saveLocation');




