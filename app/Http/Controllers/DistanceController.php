<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;

class DistanceController extends Controller
{
    //
    public function checkDistance(Request $request){
    	$data = $request->input();
    	$lat =  $data['lat'];
        $lng =  $data['lng'];
    	if($data['lat'] != null){
    		if(Auth::check()){
    			if($request->session()->get('setLocationBySettingValueAfterLogin') == null){
    				$userDetail = User::whereId(Auth()->id())->first();
					$distance = $this->distance($userDetail->customer_latitude, $userDetail->customer_longitude, $lat, $lng, "K");
					if($distance*100 > 300){
		    			DB::table('customer')->where('id', Auth::id())->update([
		                    'customer_latitude' => $data['lat'],
		                    'customer_longitude' => $data['lng'],
		                    'address' => NULL,
		                ]);
					}    				
    			}
    		}else{
    			if($request->session()->get('setLocationBySettingValue') == null){
    				$previouslat = $request->session()->get('with_out_login_lat');
                    $previouslng = $request->session()->get('with_out_login_lng');
                    $distance = $this->distance($previouslat, $previouslng, $lat, $lng, "K");
                    if($distance*100 > 300){
		                $request->session()->put('with_out_login_lat', $data['lat']);
		                $request->session()->put('with_out_login_lng', $data['lng']);
		                $request->session()->put('address', null);
                    }
                }
    		}
    	}
    	return response()->json(['status' => 'success', 'response' => true,'data'=>true]);
    }

    function distance($lat1, $lon1, $lat2, $lon2, $unit) {

		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
		  	return ($miles * 0.8684);
		} else {
		    return $miles;
		}
	}
}
