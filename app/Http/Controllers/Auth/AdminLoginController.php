<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Exceptions\SocialAuthException;
use Socialite;
use App\User;
use Auth;
use DB;
use Session;
use App\Helper;


class AdminLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function showLoginForm(){
        return view('auth.admin-login'); 
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $data = $request->input();
        if($this->guard()->attempt(
            $this->credentials($request), $request->filled('remember'))){
            if(Auth::guard('admin')->user()->language == null){
                $lang;
                if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
                  $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                  $languagesServer = explode('-', $languages[0]);
                  if($languagesServer[0] == 'sv'){
                    $lang = 'SWE';
                    Session::put('applocale', 'sv');
                  }else{
                    $lang =  'ENG';
                    Session::put('applocale', 'en');
                  }
                }
                DB::table('user')->where('id', Auth::guard('admin')->id())->update([
                            'language' => $lang,
                        ]);
            }else{
                if(Auth::guard('admin')->user()->language == 'ENG'){
                    Session::put('applocale', 'en');
                }else{
                    Session::put('applocale', 'sv');
                }
            }

        }

        DB::table('user')->where('id', Auth::guard('admin')->id())->update([
                            'browser' => $data['browser'],
                        ]);
        if(!$this->guard()->attempt($this->credentials($request), $request->filled('remember'))) {

          redirect($request->url())->with('error', 'Email id or password is incorrect');
        }
    }

    public function logout(Request $request){
        $this->guard()->logout();
        Session::forget('storeId');
        Session::forget('checkStore');
        //$request->session()->flush();

        //$request->session()->regenerate();

        return redirect('admin/login');
    }
}
