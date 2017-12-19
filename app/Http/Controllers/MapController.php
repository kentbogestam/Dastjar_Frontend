<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Company;

use App\Store;

use App\Product;

use DB;

class MapController extends Controller
{
    //

    public function searchMapEatnow(){
    	$restaurantLatLngList = Store::getRestaurantsList('27.398635','80.131693','1120','1','3');
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
    	$restaurantLatLngList = Store::getRestaurantsList('27.398635','80.131693','1120','2','3');
    	$latLng = [];
    	$i = 0;
    	foreach ($restaurantLatLngList as $restaurantLatLng) {
    		array_push($latLng,[$restaurantLatLng->latitude, $restaurantLatLng->longitude]);
    		$i++;
    	}
    	$latLngList = json_encode($latLng);
        return view('map.index', compact('latLngList'));
    }
}
