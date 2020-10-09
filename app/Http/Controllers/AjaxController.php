<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gdpr;
use App\DishType;
use App\ProductsExtra;
use App\Product;
use Carbon\Carbon;
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

    public function gdprExtra(Request $request)
    {
        $extra_product = $gdpr_val = '0';
        if(Auth::check()){
            $gdpr = new Gdpr();
            $user_id = Auth::user()->id;
            if($gdpr->where('user_id', '=', $user_id)->exists())
            {
                $gdpr_val = $gdpr->where('user_id', '=', $user_id)->first()->gdpr;
            }
        }

        $storeId = $request->storeID;
        $checkList = $request->checkList;
        $timeToday = date('H:i:s',strtotime(Carbon::now()));
        ///check for parental rule if parent exits then find its extra dish.
        if(!empty(@$checkList)){
            foreach($checkList as $list){
                $parent1 = array($list);
                if(!empty($parent1)){
                    $parent2 = DishType::where('dish_id',$parent1)->pluck('parent_id')->toArray();
                    $parent = $parent1;
                    if(!empty($parent2)){
                        $parent = array_merge($parent1,$parent2);
                        $parent3 = DishType::where('dish_id',$parent2)->pluck('parent_id')->toArray();
                        if(!empty($parent3)){
                            $parent = array_merge($parent1,$parent2,$parent3);
                        }
                    }
                }
                $productsExtra = ProductsExtra::whereIn('dish_type_id',$parent)->where('store_id',$storeId)->pluck('extra_dish_type_id')->toArray();
                
                $products = Product::from('product AS P')
                    ->select(['P.product_id', 'P.product_name', 'P.product_description', 'P.preparation_Time', 'P.small_image', 'PPL.price', 'S.extra_prep_time', 'PPL.publishing_start_time', 'PPL.publishing_end_time'])
                    ->join('product_price_list AS PPL', 'P.product_id', '=', 'PPL.product_id')
                    ->join('store AS S', 'S.store_id', '=', 'PPL.store_id')
                    ->whereIn('P.dish_type', $productsExtra)
                    ->where('PPL.store_id', $storeId)
                    ->where('PPL.publishing_start_time','<=',$timeToday)
                    ->where('PPL.publishing_end_time','>=',$timeToday)
                    ->groupBy('P.product_id')
                    ->orderBy('P.product_rank', 'ASC')
                    ->orderBy('P.product_id')
                    ->get();

                if(!empty($products->toArray())){
                    $extra_product = '1';
                } 
            }
        }
        $data = array('gdpr_val'=>$gdpr_val,'extra_product'=>$extra_product);
        return $data;
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
