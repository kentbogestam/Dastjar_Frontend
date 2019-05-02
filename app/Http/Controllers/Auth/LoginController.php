<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Exceptions\SocialAuthException;
use Socialite;
use App\User;
use App\PromotionDiscount;
use App\CustomerDiscount;
use App\Gdpr;
use Auth;
use Session;
use DB;
use Carbon\Carbon;

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
        return Socialite::driver($social)->stateless()->redirect();
    }

    public function handelProviderCallback(Request $request, $social){

        if (!$request->has('code') || $request->has('denied')) {
            return redirect('/');
        }

        try {
            // $userSocial = Socialite::with($social)->user();
            $userSocial = Socialite::driver($social)->stateless()->user();
        } catch (Exception $e) {
            return redirect('/');
        }
        
        //dd($userSocial->id);
        //$user = User::where(['email' => $userSocial->getEmail()])->first();
        $user = User::where(['fac_id' => $userSocial->getId()])->first();
        if($user){
            Auth::login($user);

            // Update user discount in cookie
            $this->updateUserDiscount();

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

            if(DB::table('customer')->where('fac_id', $userSocial->getId())->exists()){
              DB::table('customer')->where('fac_id', $userSocial->getId())->update([
                          'language' => $lang,
                      ]);              
            }
            
            if( Session::has('redirectAfterLogin') ) {
                $redirectAfterLogin = Session::get('redirectAfterLogin');
                return redirect(url($redirectAfterLogin));
            }elseif(Session::get('orderData') == null ){
                return redirect()->action('HomeController@index');
            }else{
                // return redirect()->route('withOutLogin');//commented by saurabh
                // return redirect()->route('cartWithOutLogin');
                return redirect(url('cart'));
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

            $user = User::firstOrNew(array('email' => $userSocial->getEmail()));
            $user->fac_id = $userSocial->getId();
            $user->name = $userSocial->getName();
            $user->language = $lang;
            $user->save();

            Auth::login($user);

            // Update user discount in cookie
            $this->updateUserDiscount();

            // Redirect after login where it comes from
            if( Session::has('redirectAfterLogin') )
            {
                $redirectAfterLogin = Session::get('redirectAfterLogin');
                return redirect(url($redirectAfterLogin));
            }
            else
            {
                // return redirect()->route('cartWithOutLogin');
                return redirect(url('cart'));
            }
        }
    }

    public function userLogin(Request $request){
        $data = $request->input();
        $user = User::where(['otp' => $data['otp']])->where(['phone_number' => $request->session()->get('userPhoneNumber')])->first();
        if($user){            
            Auth::login($user);

            // Update user discount in cookie
            $this->updateUserDiscount();

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
            
            // Redirect after login where it comes from
            if( Session::has('redirectAfterLogin') )
            {
                $redirectAfterLogin = Session::get('redirectAfterLogin');
                return redirect(url($redirectAfterLogin));
            }
            else
            {
                // return redirect()->route('cartWithOutLogin');
                return redirect(url('cart'));
            }
        }else{
            /*if ($request->session()->get('userPhoneNumber')) {
                Session::flash('userPhoneNumber',$request->session()->get('userPhoneNumber'));
            }else if(isset($request->userPhoneNumber)){
                Session::flash('userPhoneNumber',$request->userPhoneNumber);
            }*/
            
            return redirect()->action('Auth\LoginController@enterOtp')->with('success', 'You have entered wrong otp.');
        }
    }

    public function userSessionLogin(Request $request){
      $data = $request->input();
      $user = User::where(['id' => $data['usetId']])->first();
      if($user){
        Auth::login($user);

        // Update user discount in cookie
        $this->updateUserDiscount();

        return response()->json(['status' => 'success', 'response' => true,'data'=>true]);
      }
    }

    public function login(Request $request){
        $agent = $request->server('HTTP_USER_AGENT');

        //
        if( $request->server('HTTP_REFERER') && strpos($request->server('HTTP_REFERER'), 'user-setting') )
        {
            Session::put('redirectAfterLogin', 'user-setting');
        }
        elseif( url()->previous() && strpos(url()->previous(), 'apply-user-discount') )
        {
            // If apply promotion discount from URL
            $baseUrl = url('/');
            $previousUrl = url()->previous();
            $url = str_replace($baseUrl.'/', '', $previousUrl);
            Session::put('redirectAfterLogin', $url);
        }
        else
        {
            Session::forget('redirectAfterLogin');
        }
        
        return view('auth.login', compact('agent'));       
    }

    public function mobileLogin(){
        if(Session::has('userPhoneNumber'))
        {
            Session::forget('userPhoneNumber');
        }

        return view('auth.mobile'); 
    }

    public function enterOtp(Request $request){
        return view('auth.otp');
    }

    /**
     * Update user discount in cookie on login
     */
    function updateUserDiscount()
    {
        if(Auth::check())
        {
            // Destroy If discount is already exist in cookie
            if(isset($_COOKIE['discount']))
            {
                foreach($_COOKIE['discount'] as $key => $value)
                {
                    setcookie("discount[{$key}]", null, -1, '/');
                }
            }

            // Get if customer has discount save them in cookie while user is logging-in
            $customerDiscount = PromotionDiscount::from('promotion_discount AS PD')
                ->select(['PD.store_id', 'PD.discount_value', 'PD.start_date', 'PD.end_date'])
                ->join('customer_discount AS CD', 'PD.id', '=', 'CD.discount_id')
                ->where(['CD.customer_id' => Auth::id(), 'CD.status' => '1', 'PD.status' => '1'])
                ->where('PD.start_date', '<=', Carbon::now()->format('Y-m-d h:i:00'))
                ->where('PD.end_date', '>=', Carbon::now()->format('Y-m-d h:i:00'))
                ->get()->toArray();

            if($customerDiscount)
            {
                // Add discount in cookie
                $discount = array();

                foreach($customerDiscount as $key => $value)
                {
                    $discount = array(
                        'store_id' => $value['store_id'],
                        'discount_value' => $value['discount_value']
                    );

                    setcookie("discount[{$key}]", json_encode($discount), strtotime($value['end_date']), '/');
                    // setcookie("discount[1]", json_encode(array('store_id' => '1ce0b6b5-48f5-bc47-6c82-49223f75b137', 'discount_value' => 5)), strtotime('2019-03-30'), '/');
                }
            }
        }
    }
}
