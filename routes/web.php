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

Route::get('phpinfo', function(){
	return phpinfo();
});

Route::get('apple-date',  'AdminController@getDates');

// Auth::routes();

Route::get('logout', function(){
	\Auth::logout();
	return redirect()->route('home');
});

Route::group(['middleware' => ['preventBackHistory']], function(){
	Route::get('/admin', 'AdminController@checkStore')->name('admin');
});

// Route::get('', 'HomeController@index');
 Route::get('/home', 'HomeController@index')->name('home');

Route::post('contact-us', 'HomeController@contact_us');
Route::post('gdpr', 'AjaxController@gdpr');
Route::post('accept-gdpr', 'AjaxController@accept_gdpr');

Route::post('/delete-me', 'HomeController@deleteUser')->name('delete-user');

Route::get('/order/{id}', 'OrderController@order_detail')->name('order-url');
Route::get('/writeLogs', 'HomeController@write_logs');

Route::get('/login/{social}','Auth\LoginController@socialLogin')->where('social','facebook|google');
Route::get('/login/{social}/callback','Auth\LoginController@handelProviderCallback')->where('social','facebook|google');
Route::get('/userRegister','Auth\RegisterController@userRegister');
Route::post('/userRegisterSave','Auth\RegisterController@userDetailSave');
Route::get('/userRegisterSave','Auth\RegisterController@userDetailSave');
Route::post('/userLogin','Auth\LoginController@userLogin');
Route::get('/login','Auth\LoginController@login')->name('login');
Route::get('/login','Auth\LoginController@login')->name('customer-login');

Route::get('/go-to-login','HomeController@goToLogin');

Route::get('/mobileLogin','Auth\LoginController@mobileLogin');
Route::post('/sentOtp','Auth\RegisterController@sentOtp');
Route::get('/sentOtp','Auth\RegisterController@sentOtp');
Route::get('/enterOtp','Auth\LoginController@enterOtp');
Route::get('/userLogin','Auth\LoginController@userSessionLogin');
Route::post('/update-browser','OrderController@updateBrowser');
Route::get('/set-timezone','CustomerController@setTimezone');

Route::get('ready-notification/{OrderId}', 'PushNotifactionController@readyNotifaction');
Route::get('deliver-notification/{OrderId}', 'PushNotifactionController@deliverNotifaction');

Route::get('/test','HomeController@test');

//This is for testing stripe payment

Route::get('/redirectStripe', 'StripePaymentController@redirectStripe');
Route::get('/stripeResponse', 'StripePaymentController@stripeResponse');

Route::get('/terms', 'HomeController@terms');

//Second Phase
Route::get('/', 'HomeController@index');
Route::get('lat-long', 'HomeController@userLatLong');
Route::get('checkUserLogin', 'HomeController@checkUserLogin');
Route::get('update-location', 'HomeController@updateLocation');
Route::get('setResttype','HomeController@setRestarurantType');
Route::get('user-setting', 'CustomerController@index');
Route::post('is-valid-discount-code', 'CustomerController@ajaxIsValidDiscountCode');
Route::post('add-customer-discount', 'CustomerController@addCustomerDiscount');
Route::post('remove-customer-discount', 'CustomerController@removeCustomerDiscount');
Route::get('select-location', 'CustomerController@selectLocation');
Route::post('save-location', 'CustomerController@saveLocation');
Route::get('save-location', 'CustomerController@saveLocation');
Route::post('save-setting', 'CustomerController@saveSetting');
Route::post('store-device-token', 'CustomerController@storeDeviceToken');	
Route::post('store-device-token-order-view', 'CustomerController@storeDeviceTokenOrderView');	

Route::get('restro-menu-list/{storeID}', 'HomeController@menuList');

Route::get('search-store-map', 'MapController@searchStoreMap');
Route::get('404', 'HomeController@page_404')->name('page_404');
	Route::post('updateCart', 'OrderController@updateCart');

	Route::get('pretty-session-cookie', 'HomeController@prettySessionCookie');

Route::get('selectOrder-date', 'HomeController@selectOrderDate');
Route::group(['middleware' => ['latlng']], function(){
	Route::get('search-map-eatnow', 'MapController@searchMapEatnow');
	Route::get('eat-now', 'HomeController@index');
	Route::resource('customer', 'CustomerController');
	Route::get('saveCurrentlat-long', 'HomeController@saveCurrentLatLong');
	// Route::get('selectOrder-date', 'HomeController@selectOrderDate');
	Route::post('eat-later', 'HomeController@eatLater');
	Route::get('eat-later', 'HomeController@eatLater');
	Route::get('eat-later-data', 'HomeController@eatLaterData');
	Route::get('search-map-eatlater', 'MapController@searchMapEatlater');
	Route::get('eat-later-map', 'HomeController@eatLaterMap');
	// Route::get('withOutLogin', 'OrderController@withOutLogin')->name('withOutLogin');
	Route::get('checkDistance','DistanceController@checkDistance');
	Route::post('cart', 'OrderController@cart');
	Route::get('cart', 'OrderController@cart');
	// Route::get('cart', 'OrderController@cartWithOutLogin')->name('cartWithOutLogin');
	Route::get('view-cart/{orderId}', 'OrderController@viewCart');
	Route::post('order-update-delivery-type', 'OrderController@orderUpdateDeliveryType');
	Route::post('save-user-address', 'OrderController@saveUserAddress');
	Route::post('update-order-user-address', 'OrderController@updateOrderUserAddress');
	Route::get('get-home-delivery-part-content/{order_id}', 'OrderController@getHomeDeliveryPartContent');
	// Route::post('apply-promocode', 'OrderController@ajaxApplyPromocode');
});

Route::group(['middleware' => ['auth']], function(){
	Route::get('blank-view', 'HomeController@blankView');
	Route::get('order-view/{OrderId}', 'OrderController@orderView')->name('order-view');
	Route::get('check-if-order-accepted/{orderId}', 'OrderController@checkIfOrderAccepted');
	Route::get('check-if-order-ready', 'OrderController@checkIfOrderReady');
	Route::post('payment', 'PaymentController@payment');
	Route::get('payment', 'PaymentController@payment');
	Route::post('cancel-order', 'OrderController@cancelOrderPost');		
	Route::get('cancel-order/{order_number}', 'OrderController@cancelOrder')->name('cancel-order');		
	Route::post('save-order', 'OrderController@saveOrder');
    Route::get('save-order', 'OrderController@saveOrder');
    Route::get('emptyCart', 'OrderController@emptyCart');
});

// Kitchen (admin)
Route::group(['namespace' => 'User'], function() {
	// Promotion
	Route::group(['prefix' => 'promotion'], function() {
		Route::get('apply-user-discount/{storeId}/{discountCode}', 'PromotionController@applyUserDiscount');
	});
});

Route::prefix('admin')->group(function(){
	Route::get('login','Auth\AdminLoginController@showLoginForm');
	Route::post('login','Auth\AdminLoginController@login')->name('admin-login');
});

Route::group(['prefix' => 'kitchen'], function(){
	Route::get('checkStoreFirst', 'AdminController@checkStoreFirst');
	Route::post('store', 'AdminController@index');
	Route::get('store', 'AdminController@index');
	Route::get('logout', 'Auth\AdminLoginController@logout');
	Route::get('onReadyAjax/{OrderId}', 'AdminController@onReadyAjax');		
	
	//
	Route::group(['middleware' => 'isModuleSubscribed:kitchen'], function() {
		Route::get('kitchen-detail', 'AdminController@kitchenOrderDetail');
	});

	//
	Route::group(['middleware' => 'isModuleSubscribed:catering'], function() {
		Route::get('catering', 'AdminController@cateringDetails');
	});

	//
	Route::group(['middleware' => 'isModuleSubscribed:orderonsite'], function() {
		Route::get('kitchen-order-onsite', 'AdminController@kitchenPreOrder');
	});

	Route::get('order-started/{OrderId}', 'AdminController@orderStarted');
	Route::get('orderStartedKitchen/{OrderId}', 'AdminController@orderStartedKitchen');		
	Route::get('order-readyKitchen/{OrderId}', 'AdminController@orderReadyKitchen');

	Route::get('start-order/{id}', 'AdminController@startOrder');
	Route::get('make-order-ready/{orderId}', 'AdminController@makeOrderReady');

	Route::get('is-manual-prep-time-for-order/{orderId}', 'AdminController@isManualPrepTimeForOrder');
	Route::post('add-manual-prep-time','AdminController@addManualPrepTime');
	Route::get('get-available-driver-to-assign/{orderId}', 'AdminController@getAvailableDriverToAssign');
	Route::get('get-order-delivery-address/{addressId}', 'AdminController@getOrderDeliveryAddress');
	Route::post('order-assign-driver', 'AdminController@orderAssignDriver');
	Route::post('kitchen-order-save','AdminController@kitchenOrderSave');
	Route::get('kitchen-order-save','AdminController@kitchenOrderSave');
	Route::post('send-promotional-discount','AdminController@sendPromotionalDiscount');
	Route::post('send-promotional-app','AdminController@sendPromotionalApp');
	Route::get('selectOrder-dateKitchen', 'AdminController@selectOrderDateKitchen');
	Route::post('kitchen-eat-later', 'AdminController@kitchenEatLater');
	Route::get('kitchen-eat-later', 'AdminController@kitchenEatLater');
	Route::get('kitchen-order-view/{OrderId}', 'AdminController@kitchenOrderView');
	Route::get('order-ready/{OrderId}', 'PushNotifactionController@orderReady');
	Route::get('order-deliver/{OrderId}', 'PushNotifactionController@orderDeliver');
	Route::get('kitchen-setting', 'AdminController@kitchenSetting');
	Route::get('extra-prep-time', 'AdminController@extraPrepTime');
	Route::post('add-extra-time', 'AdminController@addExtraTime');
	Route::post('support', 'AdminController@support');

	Route::post('save-kitchenSetting', 'AdminController@saveKitchenSetting');
	Route::post('payment', 'AdminController@payment');
	Route::get('payment', 'AdminController@payment');
	Route::get('menu', 'AdminController@kitchenMenu')->name('menu');
	Route::post('ajax-get-product-by-dish-type', 'AdminController@ajaxGetProductByDishType');
	Route::post('ajax-get-future-price-by-product', 'AdminController@ajaxGetFuturePriceByProduct');
	Route::get('kitchen-orders', 'AdminController@kitchenOrders');
	Route::get('kitchen-orders-new/{lastId}', 'AdminController@kitchenOrdersNew');
	Route::get('create-menu', 'AdminController@kitchenCreateMenu')->name('create-menu');

	Route::post('create-menu-save', 'AdminController@kitchenCreateMenuPost');
	Route::post('create-menu-update', 'AdminController@kitchenUpdateMenuPost');

	Route::get('edit-menu-dish', 'AdminController@kitchenEditDish');		
	Route::get('delete-menu-dish', 'AdminController@kitchenDeleteDish');
	Route::get('delete-dish-price', 'AdminController@deleteDishPrice');	
	Route::get('createStandardOffer', 'AdminController@createStandardOffer');			
	Route::post('add-dish-price', 'AdminController@addDishPrice');	
	Route::post('is-future-date-available', 'AdminController@isFutureDateAvailable');
	Route::post('remove-order', 'AdminController@removeOrder');	
	Route::get('kitchen-menu-new/{dishId}/{storeId}', 'AdminController@kitchenMenuNew');			
	Route::post('update-order-detail-status', 'AdminController@updateOrderDetailStatus');

	//
	Route::get('check-store-subscription-plan', 'AdminController@checkStoreSubscriptionPlan');
	Route::get('order-pay-manually/{order_id}', 'AdminController@orderPayManually');

	Route::get('test-send-notifaction/{order_id}', 'AdminController@testSendNotifaction');

	Route::get('get-new-orders-detail-to-speak', 'AdminController@getNewOrdersDetailToSpeak');

	// Kitchen (admin)
	Route::group(['namespace' => 'Restaurant'], function() {
		// Discount
		Route::group(['prefix' => 'discount', 'middleware' => 'isModuleSubscribed:discount'], function() {
			Route::get('list', 'DiscountController@index');
			Route::get('get-discount-code', 'DiscountController@ajaxGetDiscountCode');
			Route::post('remote-validate-discount', 'DiscountController@remoteValidateDiscount');
			Route::post('store', 'DiscountController@store');
		});

		// Loyalty
		Route::group(['prefix' => 'loyalty', 'middleware' => 'isModuleSubscribed:loyalty'], function() {
			Route::get('list', 'LoyaltyController@index');
			Route::post('store', 'LoyaltyController@store');
			Route::get('{id}/edit', 'LoyaltyController@edit');
			Route::get('{id}/delete', 'LoyaltyController@destroy');
			Route::get('get-loyalty-by-id/{id}', 'LoyaltyController@ajaxGetLoyaltyById');
			Route::post('update', 'LoyaltyController@update');
		});

		// Dish Type
		Route::prefix('dishtype')->group(function(){
			Route::get('list', 'DishTypeController@index');
			Route::post('store', 'DishTypeController@store');
			Route::get('get-dish-type/{id}', 'DishTypeController@ajaxGetDishTypeById');
			Route::post('update', 'DishTypeController@update');
			Route::get('{id}/delete', 'DishTypeController@destroy');
		});

		// Home delivery
		Route::group(['middleware' => 'isModuleSubscribed:homedelivery'], function() {
			// Delivery price model
			Route::prefix('delivery-price-model')->group(function(){
				Route::get('list', 'DeliveryPriceModelController@index');
				Route::post('store', 'DeliveryPriceModelController@store');
				Route::get('get-delivery-price/{id}', 'DeliveryPriceModelController@ajaxGetDeliveryPriceById');
				Route::post('update', 'DeliveryPriceModelController@update');
				Route::get('{id}/delete', 'DeliveryPriceModelController@destroy');
			});

			// Driver
			Route::prefix('driver')->group(function(){
				Route::get('list', 'DriverController@index');
				Route::post('store', 'DriverController@store');
				Route::get('get-driver/{id}', 'DriverController@ajaxGetDriver');
				Route::post('update', 'DriverController@update');
				Route::get('{id}/delete', 'DriverController@destroy');
			});
		});
	});
});

// Driver
Route::group(['prefix' => 'driver'], function() {
	// Login
	Route::group(['namespace' => 'Auth'], function() {
		Route::get('/', 'DriverLoginController@index');
		Route::get('login', 'DriverLoginController@showLoginForm')->name('driver.login');
		Route::post('login', 'DriverLoginController@login')->name('driver.login.submit');
		Route::get('logout', 'DriverLoginController@logout')->name('driver.logout');
		Route::get('forget-password', 'DriverLoginController@forgetPassword');
		Route::post('reset-password', 'DriverLoginController@resetPassword');
	});

	// After login
	Route::group(['namespace' => 'Driver'], function() {
		Route::get('pickup', 'PickupController@orderPickup');
		Route::get('get-pickup-order-list', 'PickupController@getPickupOrderList');
		Route::get('order-pickup-accept/{orderDeliveryId}', 'PickupController@orderPickupAccept');
		Route::get('get-order-detail/{customerOrderId}', 'PickupController@getOrderDetail');
		Route::get('update-status/{currentStatus}', 'PickupController@updateStatus');
		Route::post('update-driver-position', 'PickupController@updateDriverPosition');

		Route::get('delivery/{orderId?}', 'DeliveryController@delivery');
		Route::get('get-deliver-order-list', 'DeliveryController@getDeliverOrderList');
		Route::get('order-deliver/{orderId}', 'DeliveryController@orderDeliver');
	});
});