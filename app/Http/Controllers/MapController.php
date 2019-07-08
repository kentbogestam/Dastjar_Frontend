<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Store;
use App\User;
use App\Product;
use DB;
use Auth;
use Session;

class MapController extends Controller
{
    public function searchMapEatnow(Request $request){
        // Nearby restaurant detail
        $nearbyRestaurantDetail = array();

        if(Auth::check())
        {
            if(Session::get('with_login_address') != null)
            {
                $loc_lat = Session::get('with_login_lat');
                $loc_lng = Session::get('with_login_lng');
            }
            else
            {
                $loc_lat = Session::get('with_out_login_lat');
                $loc_lng = Session::get('with_out_login_lng');
            }
            
            $userDetail = User::whereId(Auth()->id())->first();
            $range = $userDetail->range;
        }
        else
        {
            $loc_lat = Session::get('with_out_login_lat');
            $loc_lng = Session::get('with_out_login_lng');

            if($request->session()->get('rang') != null)
            {
                $range = $request->session()->get('rang');
            }
            else
            {
                $range = '10';
            }
        }

        //
        $latLng = [];
        array_push($latLng, array('latitude' => $loc_lat, 'longitude' => $loc_lng, 'nearbyRestaurantDetail' => array()));

        $pieces = explode(" ", $request->session()->get('current_date_time'));
        $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
        $currentTime = $pieces[4];
        $todayDay = $pieces[0];

        $restaurantLatLngList = Store::getListRestaurants($loc_lat,$loc_lng,$range,'1','3',$todayDate,$currentTime,$todayDay);

        foreach ($restaurantLatLngList as $restaurantLatLng) {
            $getTime = explode('::', $restaurantLatLng['store_open_close_day_time']);
            if(count($getTime) == 2){
                $storeTime = explode('to', $getTime[1]);
                $storeOpenTime = str_replace(':', '', str_replace(' ', '', $storeTime[0]));
                $storeCloseTime = str_replace(':', '', str_replace(' ', '', $storeTime[1]));
                $rightNowTime = str_replace(':', '', $currentTime);
                if($storeOpenTime < $currentTime && $storeCloseTime > $currentTime){
                    // Array have variable details
                    $restaurantDetail = array(
                        'store_id' => $restaurantLatLng['store_id'],
                        'store_name' => $restaurantLatLng['store_name']
                    );

                    // array_push($latLng,[$restaurantLatLng->latitude, $restaurantLatLng->longitude]);
                    array_push($latLng, array('latitude' => $restaurantLatLng->latitude, 'longitude' => $restaurantLatLng->longitude, 'nearbyRestaurantDetail' => $restaurantDetail));
                    $nearbyRestaurantDetail[] = $restaurantDetail;
                }
            }else{
                $getList = explode(',', $restaurantLatLng['store_open_close_day_time']);
                for($j=0;$j<count($getList);$j++){
                    $getTime = explode('::', $getList[$j]);
                    if(str_replace(' ', '', $getTime[0]) == $todayDay){
                        $storeTime = explode('to', $getTime[1]);
                        $storeOpenTime = str_replace(':', '', str_replace(' ', '', $storeTime[0]));
                        $storeCloseTime = str_replace(':', '', str_replace(' ', '', $storeTime[1]));
                        $rightNowTime = str_replace(':', '', $currentTime);
                        if($storeOpenTime < $currentTime && $storeCloseTime > $currentTime){
                            // Array have variable details
                            $restaurantDetail = array(
                                'store_id' => $restaurantLatLng['store_id'],
                                'store_name' => $restaurantLatLng['store_name']
                            );

                            // array_push($latLng,[$restaurantLatLng->latitude, $restaurantLatLng->longitude]);
                            array_push($latLng, array('latitude' => $restaurantLatLng->latitude, 'longitude' => $restaurantLatLng->longitude, 'nearbyRestaurantDetail' => $restaurantDetail));
                            $nearbyRestaurantDetail[] = $restaurantDetail;
                        }
                    }
                }
            }
        }

        // dd($latLng);
        $latLngList = json_encode($latLng);
        return view('map.index', compact('latLngList', 'nearbyRestaurantDetail'));
    }

    public function searchMapEatlater(Request $request){
        if($request->session()->get('order_date')){
            $pieces = explode(" ", $request->session()->get('order_date'));
            $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
            for($i=0;$i<count($month);$i++){
                if($month[$i] == $pieces[1]){
                 $months = $i;
                }
            } 
            $monthadd = $months+1;
            if($monthadd < 10){
                $monthFinal = sprintf("%02d",$monthadd);
            }else{
                $monthFinal = $monthadd;
            }
            $todayDate = $pieces[2].'-'.$monthFinal.'-'.$pieces[3];
            $currentTime = $pieces[4];
            $todayDay = $pieces[0];
        }else{
            $pieces = explode(" ", $request->session()->get('current_date_time'));
            $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
            $currentTime = $pieces[4];
            $todayDay = $pieces[0];
        }

        if(Auth::check()){
            if(Session::get('with_login_address') != null){
                $loc_lat = Session::get('with_login_lat');
                $loc_lng = Session::get('with_login_lng');
            }else{
                $loc_lat = Session::get('with_out_login_lat');
                $loc_lng = Session::get('with_out_login_lng');
            }

            $userDetail = User::whereId(Auth()->id())->first();
            $restaurantLatLngList = Store::getListRestaurants($loc_lat,$loc_lng,$userDetail->range,'2','3',$todayDate,$currentTime,$todayDay);

            $latLng = [];
            $i = 0;
            array_push($latLng,[$loc_lat, $loc_lng]);
            foreach ($restaurantLatLngList as $restaurantLatLng) {
                $getTime = explode('::', $restaurantLatLng['store_open_close_day_time']);
                if(count($getTime) == 2){
                    $storeTime = explode('to', $getTime[1]);
                    $storeOpenTime = str_replace(':', '', str_replace(' ', '', $storeTime[0]));
                    $storeCloseTime = str_replace(':', '', str_replace(' ', '', $storeTime[1]));
                    $rightNowTime = str_replace(':', '', $currentTime);
                    if($storeOpenTime < $currentTime && $storeCloseTime > $currentTime){
                        array_push($latLng,[$restaurantLatLng->latitude, $restaurantLatLng->longitude]);
                    }
                }else{
                    $getList = explode(',', $restaurantLatLng['store_open_close_day_time']);
                    for($j=0;$j<count($getList);$j++){
                        $getTime = explode('::', $getList[$j]);
                        if(str_replace(' ', '', $getTime[0]) == $todayDay){
                            $storeTime = explode('to', $getTime[1]);
                            $storeOpenTime = str_replace(':', '', str_replace(' ', '', $storeTime[0]));
                            $storeCloseTime = str_replace(':', '', str_replace(' ', '', $storeTime[1]));
                            $rightNowTime = str_replace(':', '', $currentTime);
                            if($storeOpenTime < $currentTime && $storeCloseTime > $currentTime){
                                array_push($latLng,[$restaurantLatLng->latitude, $restaurantLatLng->longitude]);
                            }
                        }
                    }
                }
                $i++;
            }
        }else{
            if($request->session()->get('rang') != null){
                $rang = $request->session()->get('rang');
            }else{
                $rang = '10';
            }
            $restaurantLatLngList = Store::getListRestaurants($request->session()->get('with_out_login_lat'),$request->session()->get('with_out_login_lng'),$rang,'2','3',$todayDate,$currentTime,$todayDay);
            $latLng = [];
            $i = 0;
            array_push($latLng,[floatval($request->session()->get('with_out_login_lat')), floatval($request->session()->get('with_out_login_lng'))]);
            foreach ($restaurantLatLngList as $restaurantLatLng) {
                $getTime = explode('::', $restaurantLatLng['store_open_close_day_time']);
                if(count($getTime) == 2){
                    $storeTime = explode('to', $getTime[1]);
                    $storeOpenTime = str_replace(':', '', str_replace(' ', '', $storeTime[0]));
                    $storeCloseTime = str_replace(':', '', str_replace(' ', '', $storeTime[1]));
                    $rightNowTime = str_replace(':', '', $currentTime);
                    if($storeOpenTime < $currentTime && $storeCloseTime > $currentTime){
                        array_push($latLng,[$restaurantLatLng->latitude, $restaurantLatLng->longitude]);
                    }
                }else{
                    $getList = explode(',', $restaurantLatLng['store_open_close_day_time']);
                    for($j=0;$j<count($getList);$j++){
                        $getTime = explode('::', $getList[$j]);
                        if(str_replace(' ', '', $getTime[0]) == $todayDay){
                            $storeTime = explode('to', $getTime[1]);
                            $storeOpenTime = str_replace(':', '', str_replace(' ', '', $storeTime[0]));
                            $storeCloseTime = str_replace(':', '', str_replace(' ', '', $storeTime[1]));
                            $rightNowTime = str_replace(':', '', $currentTime);
                            if($storeOpenTime < $currentTime && $storeCloseTime > $currentTime){
                                array_push($latLng,[$restaurantLatLng->latitude, $restaurantLatLng->longitude]);
                            }
                        }
                    }
                }
                $i++;
            }
        }
    	$latLngList = json_encode($latLng);
        return view('map.eatlater_map', compact('latLngList'));
    }

    public function searchStoreMap(Request $request){       
        $latLng = [];

        if(Auth::check()){
             if(Session::get('with_login_address') != null){
                $loc_lat = Session::get('with_login_lat');
                $loc_lng = Session::get('with_login_lng');
            }else{
                $loc_lat = Session::get('with_out_login_lat');
                $loc_lng = Session::get('with_out_login_lng');
            }

            $userDetail = User::whereId(Auth()->id())->first();
            array_push($latLng,[floatval($loc_lat), floatval($loc_lng)]);
        }else{
            array_push($latLng,[floatval($request->session()->get('with_out_login_lat')), floatval($request->session()->get('with_out_login_lng'))]);
        }

        $storedetails = Store::where('store_id' , $request->session()->get('storeId'))->first();
        array_push($latLng,[$storedetails->latitude, $storedetails->longitude]);
        $latLngList = json_encode($latLng);
        return view('map.single_res_map', compact('latLngList','storedetails'));
    }
}
