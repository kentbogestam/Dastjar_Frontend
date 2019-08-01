<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Auth;

use App\Helper;
use App\Driver;

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
     * Show forget password form
     * @return [type] [description]
     */
    function forgetPassword()
    {
        return view('auth.driver-password');
    }

    /**
     * Reset password
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function resetPassword(Request $request)
    {
        // Validate
        $this->validate($request, [
            'phone' => 'required'
        ]);

        $phone = ltrim($request->input('phone'), '0');

        // Get driver
        $driver = Driver::select(['id', 'phone_prefix', 'phone'])
            ->where('phone', $phone)
            ->first();

        if($driver)
        {
            // Generate/update password, send new password
            $password = str_random(4);
            $data['password'] = Hash::make($password);

            if(Driver::where('id', $driver->id)->update(['password' => $data['password']]))
            {
                $recipients = array();
                $recipients = [$driver->phone_prefix.$driver->phone];
                $message = "Password reset successfully. \n";
                $message .= "New password is: {$password}";
                $result = Helper::apiSendTextMessage($recipients, $message);

                return redirect('driver/login')->with('success', __('messages.passwordResetSuccessfully'));
            }
        }
        else
        {
            return redirect()->back()->withInput($request->only('phone'))->withErrors(['phone' => trans('auth.failed')]);
        }
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