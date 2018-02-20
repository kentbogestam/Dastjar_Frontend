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

    public function searchMapEatnow(Request $request){
        $userDetail = User::whereId(Auth()->id())->first();
        $pieces = explode(" ", $request->session()->get('current_date_time'));
        $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
        $currentTime = $pieces[4];
        $todayDay = $pieces[0];

    	$restaurantLatLngList = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
    	$latLng = [];
    	$i = 0;
        array_push($latLng,[$userDetail->customer_latitude, $userDetail->customer_longitude]);
    	foreach ($restaurantLatLngList as $restaurantLatLng) {
    		array_push($latLng,[$restaurantLatLng->latitude, $restaurantLatLng->longitude]);
    		$i++;
    	}
        //dd($latLng);
    	$latLngList = json_encode($latLng);
        return view('map.index', compact('latLngList'));
    }

    public function searchMapEatlater(Request $request){
        $userDetail = User::whereId(Auth()->id())->first();
        $pieces = explode(" ", $request->session()->get('current_date_time'));
        $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
        $currentTime = $pieces[4];
        $todayDay = $pieces[0];

    	$restaurantLatLngList = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'2','3',$todayDate,$currentTime,$todayDay);
    	$latLng = [];
    	$i = 0;
        array_push($latLng,[$userDetail->customer_latitude, $userDetail->customer_longitude]);
    	foreach ($restaurantLatLngList as $restaurantLatLng) {
    		array_push($latLng,[$restaurantLatLng->latitude, $restaurantLatLng->longitude]);
    		$i++;
    	}
    	$latLngList = json_encode($latLng);
        return view('map.eatlater_map', compact('latLngList'));
    }

    public function searchStoreMap(Request $request){
        $userDetail = User::whereId(Auth()->id())->first();
        $storeDetails = Store::where('store_id',$request->session()->get('storeId'.Auth()->id()))->first();
        $storedetails = Store::where('store_id' , $request->session()->get('storeId'.Auth()->id()))->first();
        $request->session()->forget('storeId'.Auth()->id());
        $latLng = [];
        array_push($latLng,[$userDetail->customer_latitude, $userDetail->customer_longitude]);
        array_push($latLng,[$storeDetails->latitude, $storeDetails->longitude]);
        $latLngList = json_encode($latLng);
        return view('map.single_res_map', compact('latLngList','storedetails'));
    }
}
