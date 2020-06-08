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

Route::group(['middleware' => ['api']], function () { 
	Route::group(['prefix' => 'v1'], function () { 
		Route::post('save-password', 'Api\v1\UsersController@savePassword');
	});
});


Route::group(['middleware' => ['api']], function () { 
	Route::group(['prefix' => 'v1/kitchen'], function () { 
		Route::get('order-detail/{storeId}', 'Api\v1\KitchenController@orderDetail');
		Route::get('updateTextspeach/{id}','Api\v1\KitchenController@updateTextspeach');
		Route::get('orderSpecificOdrderDetail/{orderId}', 'Api\v1\KitchenController@orderSpecificOrderDetail');
		Route::post('update-product-rank', 'Api\v1\KitchenController@updateProductRank');	
		Route::post('update-menu-rank', 'Api\v1\KitchenController@updateMenuRank');
	});

	// 
	Route::group(['prefix' => 'v1/homepage'], function () { 
		Route::get('get-stores-by-user/{uId}', 'Api\v1\RestaurantController@getStoresByUser');
		Route::get('get-store/{storeId}', 'Api\v1\RestaurantController@getStore');
		Route::get('get-store-delivery-price-model/{storeId}', 'Api\v1\RestaurantController@getStoreDeliveryPriceModel');
		Route::get('get-store-packages/{storeId}', 'Api\v1\RestaurantController@getStorePackages');
	});
});
