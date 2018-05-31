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

	Auth::routes();

	Route::get('logout', function(){
		\Auth::logout();
		return redirect()->route('home');
	});
	
	Route::group(['middleware' => ['preventBackHistory']], function(){
		Route::get('/admin', 'AdminController@checkStore')->name('admin');
	});

	Route::get('/home', 'HomeController@index')->name('home');

	Route::post('contact-us', 'HomeController@contact_us');
	Route::post('gdpr', 'AjaxController@gdpr');
	Route::post('accept-gdpr', 'AjaxController@accept_gdpr');

	Route::post('/delete-me', 'HomeController@deleteUser')->name('delete-user');
	
	Route::get('/order/{id}', 'OrderController@order_detail')->name('order-url');

	Route::get('/login/{social}','Auth\LoginController@socialLogin')->where('social','facebook|google');
	Route::get('/login/{social}/callback','Auth\LoginController@handelProviderCallback')->where('social','facebook|google');
	Route::get('/userRegister','Auth\RegisterController@userRegister');
	Route::post('/userRegisterSave','Auth\RegisterController@userDetailSave');
	Route::get('/userRegisterSave','Auth\RegisterController@userDetailSave');
	Route::post('/userLogin','Auth\LoginController@userLogin');
	Route::get('/mobileLogin','Auth\LoginController@mobileLogin');
	Route::post('/sentOtp','Auth\RegisterController@sentOtp');
	Route::get('/sentOtp','Auth\RegisterController@sentOtp');
	Route::get('/enterOtp','Auth\LoginController@enterOtp');
	Route::get('/userLogin','Auth\LoginController@userSessionLogin');

   //This is for testing stripe payment

	Route::get('/redirectStripe', 'StripePaymentController@redirectStripe');
	Route::get('/stripeResponse', 'StripePaymentController@stripeResponse');
	
	Route::get('getList', 'HomeController@getList');

	//Second Phase
	Route::get('/', 'HomeController@index');
	Route::get('lat-long', 'HomeController@userLatLong');
	Route::get('checkUserLogin', 'HomeController@checkUserLogin');
	Route::group(['middleware' => ['latlng']], function(){
		Route::get('search-map-eatnow', 'MapController@searchMapEatnow');
		Route::get('eat-now', 'HomeController@index');
		Route::get('user-setting', 'CustomerController@index');
		Route::get('select-location', 'CustomerController@selectLocation');
		Route::post('save-location', 'CustomerController@saveLocation');
		Route::get('save-location', 'CustomerController@saveLocation');
		Route::resource('customer', 'CustomerController');
		Route::get('saveCurrentlat-long', 'HomeController@saveCurrentLatLong');
		Route::post('save-setting', 'CustomerController@saveSetting');
		Route::get('selectOrder-date', 'HomeController@selectOrderDate');
		Route::post('eat-later', 'HomeController@eatLater');
		Route::get('eat-later', 'HomeController@eatLater');
		Route::get('eat-later-data', 'HomeController@eatLaterData');
		Route::get('search-map-eatlater', 'MapController@searchMapEatlater');
		Route::get('eat-later-map', 'HomeController@eatLaterMap');
		Route::get('restro-menu-list/{storeID}', 'HomeController@menuList');
		Route::get('search-store-map', 'MapController@searchStoreMap');
		Route::post('save-order', 'OrderController@saveOrder');
		Route::get('save-order', 'OrderController@saveOrder');
		Route::get('withOutLogin', 'OrderController@withOutLogin')->name('withOutLogin');
		Route::get('checkDistance','DistanceController@checkDistance');
	});

Route::group(['middleware' => ['auth']], function(){
	Route::get('ready-notifaction/{OrderId}', 'PushNotifactionController@readyNotifaction');
	Route::get('deliver-notifaction/{OrderId}', 'PushNotifactionController@deliverNotifaction');
	Route::get('blank-view', 'HomeController@blankView');
	Route::get('order-view/{OrderId}', 'OrderController@orderView');
	Route::post('payment', 'PaymentController@payment');
	Route::get('payment', 'PaymentController@payment');
	
});

	Route::prefix('admin')->group(function(){
		Route::get('login','Auth\AdminLoginController@showLoginForm');
		Route::post('login','Auth\AdminLoginController@login')->name('admin-login');
	});


	Route::group(['prefix' => 'kitchen'], function(){
		Route::post('store', 'AdminController@index');
		Route::get('store', 'AdminController@index');
		Route::get('checkStoreFirst', 'AdminController@checkStoreFirst');
		Route::get('logout', 'Auth\AdminLoginController@logout');
		Route::get('order-detail', 'AdminController@orderDetail');
		Route::get('kitchen-detail', 'AdminController@kitchenOrderDetail');
		Route::get('kitchen-orders', 'AdminController@kitchenOrders');
		Route::get('catering', 'AdminController@cateringDetails');
		Route::get('catering-orders', 'AdminController@cateringOrders');
		Route::get('kitchen-order-onsite', 'AdminController@kitchenPreOrder');
		Route::get('order-started/{OrderId}', 'AdminController@orderStarted');
		Route::get('orderStartedKitchen/{OrderId}', 'AdminController@orderStartedKitchen');
		Route::get('order-readyKitchen/{OrderId}', 'AdminController@orderReadyKitchen');
		Route::get('onReadyAjax/{OrderId}', 'AdminController@onReadyAjax');
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
		Route::get('updateTextspeach/{id}','AdminController@updateTextspeach');
		Route::post('payment', 'AdminController@payment');
		Route::get('payment', 'AdminController@payment');
		Route::get('kitchen-orders-new/{lastId}', 'AdminController@kitchenOrdersNew');
		Route::get('orderSpecificOdrderDetail/{orderId}', 'AdminController@orderSpecificOdrderDetail');
	});




