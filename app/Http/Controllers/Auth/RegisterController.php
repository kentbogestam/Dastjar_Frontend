<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Country;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    public function userRegister()
    {

        return view('auth.register');
    }

    public function userDetailSave(Request $request){
        if(!empty($request->input())){

            $validator = $this->validator($request->all());
            if ($validator->fails()) {
                return redirect()->action('Auth\RegisterController@userRegister')->with('success', 'This Email already register.');
            }
            $user = $this->create($request->all());
            if ($user) {


                // Define recipients
                $afterRemoveFirstZeroNumber = substr($user->phone_number, -9);
                $recipients = ['+'.$user->phone_number_prifix.$afterRemoveFirstZeroNumber];
                $url = "https://gatewayapi.com/rest/mtsms";
                $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";
                $message = $user->otp;
                $json = [
                    'sender' => 'Dastjar',
                    'message' => ''.$message.'',
                    'recipients' => [],
                ];
                foreach ($recipients as $msisdn) {
                    $json['recipients'][] = ['msisdn' => $msisdn];}

                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                curl_setopt($ch,CURLOPT_USERPWD, $api_token.":");
                curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($json));
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);
                // print($result);
                $json = json_decode($result);
                // print_r($json->ids);
                if($json->message == 'Insufficient credit'){
                    return redirect()->action('Auth\LoginController@mobileLogin')->with('success', 'Otp is not sent due to some technical issue.');
                }else{
                    return view('auth.otp');
                }
            }
            return redirect('/')->with('success', 'Your EmailId already exist.');

        }else{
            return redirect()->action('Auth\RegisterController@userRegister')->with('success', 'This Email already register.');
        }
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customer',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create($data);
        $user->otp = rand(1000, 9999);
        $user->save();
        return $user;
    }

    public function sentOtp(Request $request){

        if(!empty($request->input())){

            $data = $request->input();
            $user = User::where(['phone_number' => $data['mobileNo']])->first();
            if($user){
                $afterRemoveFirstZeroNumber = substr($user->phone_number, -9);
                $recipients = ['+'.$user->phone_number_prifix.$afterRemoveFirstZeroNumber];
                //dd($recipients);
                $url = "https://gatewayapi.com/rest/mtsms";
                $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";
                $message = $user->otp;
                $json = [
                    'sender' => 'Dastjar',
                    'message' => ''.$message.'',
                    'recipients' => [],
                ];
                foreach ($recipients as $msisdn) {
                    $json['recipients'][] = ['msisdn' => $msisdn];}

                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                curl_setopt($ch,CURLOPT_USERPWD, $api_token.":");
                curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($json));
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);   
               // print($result);
                // $json = json_decode($result);
                // dd($json);
                //print($result);
                $json = json_decode($result);
                if($json->message == 'Insufficient credit'){
                    return redirect()->action('Auth\LoginController@mobileLogin')->with('success', 'Otp is not sent due to some technical issue.');
                }else{
                    return view('auth.otp');
                }
                // print_r($json->ids);
            }
            return redirect()->action('Auth\RegisterController@userRegister')->with('success', 'Your Number is not register.Please register mobile number');

        }else{
            return view('auth.otp');
        }
    }
}
