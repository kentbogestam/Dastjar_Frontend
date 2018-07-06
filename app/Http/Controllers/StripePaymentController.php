<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    public function redirectStripe(){
    	return redirect()->away('https://connect.stripe.com/oauth/authorize?response_type=code&client_id=' . env('STRIPE_CLIENT_ID') . '&scope=read_write');
    }

    public function stripeResponse(Request $request){
        $data=$request->input();
        
        dd($data);
        
        $url = 'https://connect.stripe.com/oauth/token/client_secret=' . env('STRIPE_SECRET_KEY') . '/code='.$data['code'].'/grant_type=authorization_code';

    	$curl = curl_init();
 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array_merge($data1,$data)));
        // $errno = curl_errno($curl);
        //  $error_message = curl_strerror($errno);
        //  dd($error_message);
        $response = curl_exec($curl);
        dd($response);
        
     //    curl_close($curl);
    	return redirect()->away(env('APP_URL').'stripeResponse?scope='.$data['scope'].'&code='.$data['code'].'');
    }

    public function stripeSecondResponse(Request $request){
    	dd($request->input());
    }
}
