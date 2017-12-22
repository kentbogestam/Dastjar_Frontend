<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Company;

use App\Store;

use App\User;

use App\Product;

use DB;

use Auth;

class MapController extends Controller
{
    //

    public function searchMapEatnow(){
        $userDetail = User::whereId(Auth()->id())->first();
    	$restaurantLatLngList = Store::getRestaurantsList($userDetail->customer_latitude,$userDetail->customer_longitude,'1','1','3');
    	$latLng = [];
    	$i = 0;
    	foreach ($restaurantLatLngList as $restaurantLatLng) {
    		array_push($latLng,[$restaurantLatLng->latitude, $restaurantLatLng->longitude]);
    		$i++;
    	}
    	$latLngList = json_encode($latLng);
        return view('map.index', compact('latLngList'));
    }

    public function searchMapEatlater(){
        $userDetail = User::whereId(Auth()->id())->first();
    	$restaurantLatLngList = Store::getRestaurantsList($userDetail->customer_latitude,$userDetail->customer_longitude,'1','2','3');
    	$latLng = [];
    	$i = 0;
    	foreach ($restaurantLatLngList as $restaurantLatLng) {
    		array_push($latLng,[$restaurantLatLng->latitude, $restaurantLatLng->longitude]);
    		$i++;
    	}
    	$latLngList = json_encode($latLng);
        return view('map.eatlater_map', compact('latLngList'));
    }
}
