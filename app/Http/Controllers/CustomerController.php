<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Auth;
use App\Helper;
use Session;
use Cache;
use App\App42\App42API;

class CustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.index', compact(''));
    }


    public function saveSetting(Request $request){
        $data = $request->input();
      
        if($data['radio-choice-v-2'] == 'ENG'){
            Session::put('applocale', 'en');
            $lang =  'ENG';
        }else{
            Session::put('applocale', 'sv');
            $lang = 'SWE';
        }

        if(Auth::check()){
            DB::table('customer')->where('id', Auth::id())->update([
                'language' => $data['radio-choice-v-2'],
                'range' => $data['range-1b'],
            ]);
        }else{
            $request->session()->put('sessionBrowserLanguageValue', 1);
            $request->session()->put('browserLanguageWithOutLogin', $lang);
            $request->session()->put('rang', $data['range-1b']);
        }
        return redirect('customer')->with('success', 'Setting updated successfully.');
    }

    public function selectLocation(){
        return view('settings.location', compact(''));
    }

    public function saveLocation(Request $request){
        if(!empty($request->input())){

            $data = $request->input();
            $address = Helper::getLocation($data['street_address']);
            //dd($address['latitude'] != null && $address['longitude'] != null);
            if($address['latitude'] != null && $address['longitude'] != null){

                if(Auth::check()){
                    // DB::table('customer')->where('id', Auth::id())->update([
                    //     'customer_latitude' => $address['latitude'],
                    //     'customer_longitude' => $address['longitude'],
                    //     'address' => $address['street_address'],
                    // ]);
                    $request->session()->put('with_login_lat', $address['latitude']);
                    $request->session()->put('with_login_lng', $address['longitude']);
                    $request->session()->put('with_login_address', $address['street_address']);
                    $request->session()->put('updateLocationBySettingAfterLogin', 1);
                    $request->session()->put('setLocationBySettingValueAfterLogin', 1);
                }else{
                    $helper = new Helper();
                    $helper->logs("set location " . $address['latitude'] . " " . $address['longitude']);

                    $request->session()->put('with_out_login_lat', $address['latitude']);
                    $request->session()->put('with_out_login_lng', $address['longitude']);
                    $request->session()->put('address', $address['street_address']);
                    $request->session()->put('setLocationBySettingValue', 1);
                }
            }
        }

        if($data['redirect_to_home'] == 1){
            return redirect('home')->with('success', 'Location updated successfully.');
        }else{
            return redirect('customer')->with('success', 'Location updated successfully.');
        }
    }


    public function storeDeviceToken(Request $request){
        $helper = new Helper();

        if(User::where('email',$request->email)->first()->device_token == null){
            $cust = User::where('email',$request->email)->first(); 
            $cust->device_token = $request->deviceToken;
            $cust->save();
            $response = "device id doesnt exist";
            $helper->logs($request->email . " " . $request->deviceToken);
        }else if(User::where('email',$request->email)->whereRaw("find_in_set('$request->deviceToken',device_token)")->doesntExist()){
            $cust = User::where('email',$request->email)->first(); 
            $cust->device_token =  $cust->device_token.",".$request->deviceToken;
            $cust->save();
            $response = "device id doesnt exist";
            $helper->logs($request->email . " " . $request->deviceToken);            
        }else{
            $response = "device id " . $request->deviceToken . " exist";
            $helper->logs($request->email . " " . $response);
        }

        return response()->json(['status' => 'success', 'response' => $response,'data'=>true]);        
    }

    public function setTimezone(Request $request){
        Session::put('timezone',$request->tz);
        return response()->json(['status' => 'success', 'response' => 'timezone is ' . $request->tz,'data'=>true]);        
    }
}
