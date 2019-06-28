<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class DriverLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:driver', ['except' => ['logout']]);
    }

    function index()
    {
        return redirect('driver/login');
    }

    /**
     * Login page
     * @return [type] [description]
     */
    public function showLoginForm()
    {
        return view('auth.driver-login');
    }

    /**
     * Submit login form
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function login(Request $request)
    {
        // Validate
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //
        if(Auth::guard('driver')->attempt(['email' => $request->email, 'password' => $request->password]))
        {
            return redirect('driver/pickup');
        }
        
        return redirect()->back()->withInput($request->only('email'))->withErrors(['email' => trans('auth.failed')]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout()
    {
        Auth::guard('driver')->logout();
        return redirect('driver/login');
    }
}