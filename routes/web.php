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
	Route::get('/admin', 'AdminController@index')->name('admin');

	Route::get('/login/{social}','Auth\LoginController@socialLogin')->where('social','facebook|google');
	Route::get('/login/{social}/callback','Auth\LoginController@handelProviderCallback')->where('social','facebook|google');

Route::group(['middleware' => ['auth']], function(){

	Route::get('ready-notifaction/{OrderId}', 'PushNotifactionController@readyNotifaction');
	Route::get('deliver-notifaction', 'PushNotifactionController@deliverNotifaction');

	Route::get('/', 'HomeController@index');
	Route::get('blank-view', 'HomeController@blankView');
	Route::post('eat-later', 'HomeController@eatLater');
	Route::get('eat-later', 'HomeController@eatLater');
	Route::get('eat-later-data', 'HomeController@eatLaterData');
	Route::get('selectOrder-date', 'HomeController@selectOrderDate');
	Route::get('eat-later-map', 'HomeController@eatLaterMap');
	Route::get('eat-now', 'HomeController@index');
	Route::get('restro-menu-list/{storeID}', 'HomeController@menuList');
	Route::get('search-map-eatnow', 'MapController@searchMapEatnow');
	Route::get('search-store-map', 'MapController@searchStoreMap');
	Route::get('search-map-eatlater', 'MapController@searchMapEatlater');
	Route::post('save-order', 'OrderController@saveOrder');
	Route::get('save-order', 'OrderController@saveOrder');
	Route::get('order-view/{OrderId}', 'OrderController@orderView');
	Route::get('lat-long', 'HomeController@userLatLong');
	Route::get('user-setting', 'CustomerController@index');
	Route::post('save-setting', 'CustomerController@saveSetting');
	Route::resource('customer', 'CustomerController');
	Route::get('select-location', 'CustomerController@selectLocation');
	Route::post('save-location', 'CustomerController@saveLocation');
	Route::get('save-location', 'CustomerController@saveLocation');
	
});

	Route::prefix('admin')->group(function(){
		Route::get('login','Auth\AdminLoginController@showLoginForm');
		Route::post('login','Auth\AdminLoginController@login')->name('admin-login');
	});


	Route::group(['prefix' => 'kitchen'], function(){
		Route::get('order-detail', 'AdminController@orderDetail');
		Route::get('kitchen-detail', 'AdminController@kitchenOrderDetail');
		Route::get('kitchen-orders', 'AdminController@kitchenOrders');
		Route::get('catering', 'AdminController@cateringDetails');
		Route::get('catering-orders', 'AdminController@cateringOrders');
		Route::get('kitchen-order-onside', 'AdminController@kitchenPreOrder');
		Route::get('order-started/{OrderId}', 'AdminController@orderStarted');
		Route::get('order-readyKitchen/{OrderId}', 'AdminController@orderReadyKitchen');
		Route::post('kitchen-order-save','AdminController@kitchenOrderSave');
		Route::get('kitchen-order-save','AdminController@kitchenOrderSave');
		Route::get('selectOrder-dateKitchen', 'AdminController@selectOrderDateKitchen');
		Route::post('kitchen-eat-later', 'AdminController@kitchenEatLater');
		Route::get('kitchen-eat-later', 'AdminController@kitchenEatLater');
		Route::get('kitchen-order-view/{OrderId}', 'AdminController@kitchenOrderView');
		Route::get('order-ready/{OrderId}', 'PushNotifactionController@orderReady');
		Route::get('order-deliver/{OrderId}', 'PushNotifactionController@orderDeliver');
		Route::get('kitchen-setting', 'AdminController@kitchenSetting');
		Route::post('save-kitchenSetting', 'AdminController@saveKitchenSetting');
	});




