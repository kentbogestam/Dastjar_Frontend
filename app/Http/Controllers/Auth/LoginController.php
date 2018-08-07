<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Exceptions\SocialAuthException;
use Socialite;
use App\User;
use App\Gdpr;
use Auth;
use Session;
use DB;

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
            $userSocial = Socialite::with($social)->user();
        } catch (Exception $e) {
            return redirect('/');
        }
        
        //dd($userSocial->id);
        //$user = User::where(['email' => $userSocial->getEmail()])->first();
        $user = User::where(['fac_id' => $userSocial->id])->first();
        if($user){
            Auth::login($user);

            $cookie_name = "gdpr";
            if(isset($_COOKIE[$cookie_name])) {
                $user_id = Auth::user()->id;
                $user_gdpr = Gdpr::firstOrNew(['user_id' => $user_id]);
                $user_gdpr->gdpr = 1;
                $user_gdpr->save();
                setcookie($cookie_name, "", time() - 3600);
            }
            
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

            if(DB::table('customer')->where('fac_id', $userSocial->id)->exists()){
              DB::table('customer')->where('fac_id', $userSocial->id)->update([
                          'language' => $lang,
                      ]);              
            }
            
            if(Session::get('orderData') == null ){
              return redirect()->action('HomeController@index');
            }else{
                return redirect()->route('withOutLogin');
            }
        }else{
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

            $user = User::firstOrNew(array('email' => $userSocial->email));
            $user->fac_id = $userSocial->id;
            $user->name = $userSocial->name;
            $user->language = $lang;
            $user->save();

            Auth::login($user);

            return redirect()->route('withOutLogin');
        }
    }

    public function userLogin(Request $request){
        $data = $request->input();
        $user = User::where(['otp' => $data['otp']])->where(['phone_number' => $request->session()->get('userPhoneNumber')])->first();
        if($user){            
            Auth::login($user);

            $cookie_name = "gdpr";
            if(isset($_COOKIE[$cookie_name])) {
                $user_id = Auth::user()->id;
                $user_gdpr = Gdpr::firstOrNew(['user_id' => $user_id]);
                $user_gdpr->gdpr = 1;
                $user_gdpr->save();
                setcookie($cookie_name, "", time() - 3600);
            }

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
            DB::table('customer')->where('otp', $data['otp'])->update([
                        'language' => $lang,
                    ]);
            //return redirect()->action('HomeController@index');
            return redirect()->route('withOutLogin');
        }else{
          if ($request->session()->get('userPhoneNumber')) {
            Session::flash('userPhoneNumber',$request->session()->get('userPhoneNumber'));
          }else if(isset($request->userPhoneNumber)){
            Session::flash('userPhoneNumber',$request->userPhoneNumber);
          }
            
            return redirect()->action('Auth\LoginController@enterOtp')->with('success', 'You have entered wrong otp.');
        }
    }

    public function userSessionLogin(Request $request){
      $data = $request->input();
      $user = User::where(['id' => $data['usetId']])->first();
      if($user){
        Auth::login($user);
        return response()->json(['status' => 'success', 'response' => true,'data'=>true]);
      }
    }

    public function mobileLogin(){
        return view('auth.mobile'); 
    }

    public function enterOtp(Request $request){
        return view('auth.otp');
    }
}
