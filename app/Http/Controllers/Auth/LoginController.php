<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Exceptions\SocialAuthException;
use Socialite;
use App\User;
use Auth;
use Session;

class LoginController extends Controller
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function socialLogin($social){
        return Socialite::driver($social)->redirect();
    }

    public function handelProviderCallback(Request $request, $social){

        if (!$request->has('code') || $request->has('denied')) {
            return redirect('/');
        }

        try {
            $userSocial = Socialite::driver($social)->user();
        } catch (Exception $e) {
            return redirect('/');
        }
        
        //dd($userSocial);
        //$user = User::where(['email' => $userSocial->getEmail()])->first();
        $user = User::where(['fac_id' => $userSocial->id])->first();
        if($user){
            Auth::login($user);
            if($user->language == 'ENG'){
                Session::put('applocale', 'en');
            }else{
                Session::put('applocale', 'sv');
            }
            return redirect('/');
            //return redirect()->action('HomeController@index');
        }else{
            $user = User::create([
                    'fac_id' => $userSocial->id,
                    'name' => $userSocial->name,
                    'email' => $userSocial->email,
                ]);
            Auth::login($user);
            return redirect()->action('HomeController@index');
        }
    }
}
