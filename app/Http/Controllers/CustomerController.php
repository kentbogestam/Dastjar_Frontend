<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Auth;
use App\Helper;
use Session;
use Cache;

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
                    $request->session()->put('with_out_login_lat', $address['latitude']);
                    $request->session()->put('with_out_login_lng', $address['longitude']);
                    $request->session()->put('address', $address['street_address']);
                    $request->session()->put('setLocationBySettingValue', 1);
                }
            }
        }
        return redirect('customer')->with('success', 'Location updated successfully.');
        //return view('settings.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
