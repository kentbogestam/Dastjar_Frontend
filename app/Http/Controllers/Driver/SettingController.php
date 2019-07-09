<?php

namespace App\Http\Controllers\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Auth;

use App\Driver;

class SettingController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:driver');
    }

    /**
     * Settings
     * @return [type] [description]
     */
    function setting()
    {
    	$driverId = Auth::guard('driver')->user()->id;

    	$driver = Driver::where('id', $driverId)->first();

    	return view('driver.setting', compact('driver'));
    }

    /**
     * Update driver
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function updateDriver(Request $request)
    {
    	// Validate
        $this->validate($request, [
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        // 
        $driverId = Auth::guard('driver')->user()->id;
        $data = $request->except('_token');

        Driver::where('id', $driverId)->update(['phone' => $data['phone'], 'email' => $data['email']]);
        return redirect('driver/setting')->with('success', __('messages.driverUpdatedSuccessfully'));
    }

    /**
     * Update password
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function changePassword(Request $request)
    {
    	// Validate
        $this->validate($request, [
            'password' => 'required|confirmed|min:5',
        ]);

        // 
        $driverId = Auth::guard('driver')->user()->id;
        $data['password'] = Hash::make($request->input('password'));

        Driver::where('id', $driverId)->update(['password' => $data['password']]);
        return redirect('driver/setting')->with('success', __('messages.passwordUpdatedSuccessfully'));
    }
}
