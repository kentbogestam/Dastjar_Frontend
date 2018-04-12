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
        if(Auth::check()){
            $userDetail = User::whereId(Auth()->id())->first();
            $pieces = explode(" ", $request->session()->get('current_date_time'));
            $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
            $currentTime = $pieces[4];
            $todayDay = $pieces[0];

            $restaurantLatLngList = Store::getListRestaurants($request->session()->get('with_login_lat'),$request->session()->get('with_login_lng'),$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
            $latLng = [];
            $i = 0;
            array_push($latLng,[$request->session()->get('with_login_lat'), $request->session()->get('with_login_lng')]);
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
                //echo($restaurantLatLng['store_open_close_day_time']);
                $i++;
            }
            $latLngList = json_encode($latLng);
            return view('map.index', compact('latLngList'));
        }else{

            $pieces = explode(" ", $request->session()->get('current_date_time'));
            $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
            $currentTime = $pieces[4];
            $todayDay = $pieces[0];
            if($request->session()->get('rang') != null){
                $rang = $request->session()->get('rang');
            }else{
                $rang = '7';
            }
            $restaurantLatLngList = Store::getListRestaurants($request->session()->get('with_out_login_lat'),$request->session()->get('with_out_login_lng'),$rang,'1','3',$todayDate,$currentTime,$todayDay);
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
                //echo($restaurantLatLng['store_open_close_day_time']);
                $i++;
            }
            //dd($latLng);
            $latLngList = json_encode($latLng);
            return view('map.index', compact('latLngList'));
        }
    }

    public function searchMapEatlater(Request $request){
        $pieces = explode(" ", $request->session()->get('current_date_time'));
        $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
        $currentTime = $pieces[4];
        $todayDay = $pieces[0];

        if(Auth::check()){
            $userDetail = User::whereId(Auth()->id())->first();
            $restaurantLatLngList = Store::getListRestaurants($request->session()->get('with_login_lat'),$request->session()->get('with_login_lng'),$userDetail->range,'2','3',$todayDate,$currentTime,$todayDay);

            $latLng = [];
            $i = 0;
            array_push($latLng,[$request->session()->get('with_login_lat'), $request->session()->get('with_login_lng')]);
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
                //echo($restaurantLatLng['store_open_close_day_time']);
                $i++;
            }
        }else{
            if($request->session()->get('rang') != null){
                $rang = $request->session()->get('rang');
            }else{
                $rang = '7';
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
                //echo($restaurantLatLng['store_open_close_day_time']);
                $i++;
            }
        }
    	$latLngList = json_encode($latLng);
        return view('map.eatlater_map', compact('latLngList'));
    }

    public function searchStoreMap(Request $request){
       
        $latLng = [];
        if(Auth::check()){
            $userDetail = User::whereId(Auth()->id())->first();
            array_push($latLng,[floatval($request->session()->get('with_login_lat')), floatval($request->session()->get('with_login_lng'))]);
        }else{
            array_push($latLng,[floatval($request->session()->get('with_out_login_lat')), floatval($request->session()->get('with_out_login_lng'))]);
        }
        $storeDetails = Store::where('store_id',$request->session()->get('storeId'))->first();
        $storedetails = Store::where('store_id' , $request->session()->get('storeId'))->first();
        array_push($latLng,[$storeDetails->latitude, $storeDetails->longitude]);
        $latLngList = json_encode($latLng);
        return view('map.single_res_map', compact('latLngList','storedetails'));
    }
}
