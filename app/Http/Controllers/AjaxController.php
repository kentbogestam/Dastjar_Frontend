<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gdpr;
use App\Product;
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

    public function kitchenCrt(Request $request){
        $product = new Product();
        $data = ['product_name' => $request->prodName,
                'small_image' => $request->prodImage,
                'large_image' => $request->prodImage,
                'dish_type' => $request->dishType,
                'product_description' => $request->prodDesc,
                'preparation_Time' => $request->prepTime,
                'start_of_publishing' => $request->publish_start_date];

        $product->create($data);
        // return redirect()->action('AdminController@index')->with('success', 'Order Ready Notifaction Send Successfully.');

        // return redirect()->route('create-menu')->with('success', 'Dish Created Successfully.');
    }
}
