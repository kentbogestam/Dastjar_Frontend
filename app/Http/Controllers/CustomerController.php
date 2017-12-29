<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Auth;
use App\Helper;

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
        DB::table('customer')->where('id', Auth::id())->update([
                    'language' => $data['radio-choice-v-2'],
                    'range' => $data['range-1b'],
                ]);
        return redirect('customer')->with('success', 'Setting updated successfully.');
        //return view('settings.index', compact(''));
        //dd($data['radio-choice-v-2']);
        //dd($data['range-1b']);
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

                DB::table('customer')->where('id', Auth::id())->update([
                        'customer_latitude' => $address['latitude'],
                        'customer_longitude' => $address['longitude'],
                        'address' => $address['street_address'],
                    ]);
            }
        }
        return view('settings.index', compact(''));
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
