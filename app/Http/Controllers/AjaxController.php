<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gdpr;
use Auth;

class AjaxController extends Controller
{
    public function gdpr(){
        if(Auth::check()){
            $gdpr = new Gdpr();

            $user_id = Auth::user()->id;

            if($gdpr->where('user_id', '=', $user_id)->exists()){
            $gdpr_val = $gdpr->where('user_id', '=', $user_id)->first()->gdpr;
            return $gdpr_val;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    public function accept_gdpr(){
        if(Auth::check()){
            $user_id = Auth::user()->id;

            $user_gdpr = Gdpr::firstOrNew(['user_id' => $user_id]);
            $user_gdpr->gdpr = 1;
            $user_gdpr->save();

            if($user_gdpr){
                return 1;
            }else{
                return 0;
            }
       }else{
           $cookie_name = "gdpr";
           $cookie_value = 1;
           setcookie($cookie_name, $cookie_value, time() + 3600, "/"); // 86400 = 1 day
           return 1;
        }
    }
}
