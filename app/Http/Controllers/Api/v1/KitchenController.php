<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Admin;
use DB;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\Store;
use Carbon\Carbon;
use Auth;
use App\User;

class KitchenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

   public function orderDetail($reCompanyId){
        $orderDetailscustomer = Order::select('orders.*','customer.name as name')->where(['store_id' => $reCompanyId])->where('user_type','=','customer')->where('check_deliveryDate',Carbon::now()->toDateString())->where('orders.paid', '0')->whereNotIn('orders.online_paid', [2])->leftJoin('customer','orders.user_id','=','customer.id');

        $orderDetails = Order::select('orders.*','user.fname as name')->where('orders.store_id', '=' ,$reCompanyId)->where('user_type','=','admin')->where('check_deliveryDate',Carbon::now()->toDateString())->where('orders.paid', '0')->whereNotIn('orders.online_paid', [2])->leftJoin('user','orders.user_id','=','user.id');

        $extra_prep_time = Store::where('store_id', $reCompanyId)->first()->extra_prep_time;
        
        $results = $orderDetailscustomer->union($orderDetails)->get();

        return response()->json(['status' => 'success', 'response' => true, 'extra_prep_time' => $extra_prep_time, 'data'=>$results]);
    }
    
    public function updateTextspeach($id){
        DB::table('order_details')->where('id', $id)->update([
                    'is_speak' => 1,
                ]);
         return response()->json(['status' => 'success', 'response' => true,'data'=>$id]);
    }

    public function orderSpecificOrderDetail($orderId){
         $orderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time','orders.customer_order_id','orders.online_paid')->where(['order_details.order_id' => $orderId])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->get();
        return response()->json(['status' => 'success', 'data'=>$orderDetails]);
    }

    public function cateringOrders($reCompanyId){
        $cateringorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time', 'orders.customer_order_id','orders.online_paid')->where(['order_details.store_id' => $reCompanyId])->where('order_details.delivery_date','>', Carbon::now()->toDateString())->whereNotIn('orders.online_paid', [2])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->orderBy('order_details.delivery_date','ASC')->get();
    
        return response()->json(['status' => 'success', 'response' => true,'data'=>$cateringorderDetails]);
    }

    public function updateProductRank(Request $request){
        $product = new Product();
/*
        if($request->index == 0){
            $product->where('dish_type',$request->dish_type)->where('rank',1)->where('product_id','>',$request->product_id)->limit(1)->update(['rank' => $request->index+1]);
            $product->where('product_id',$request->product_id)->update(['rank' => $request->index]);
        }else{
            $product->where('dish_type',$request->dish_type)->where('rank',$request->index)->update(['rank' => $request->index+1]);
            $product->where('product_id',$request->product_id)->update(['rank' => $request->index]);
            $product->where('dish_type',$request->dish_type)->where('rank',1)->where('product_id','>',$request->product_id)->limit(1)->update(['rank' => $request->index+1]);
        }
*/
/*
        if($request->index == 1){
            $product->where('dish_type',$request->dish_type)->where('rank',1)->where('product_id','>',$request->product_id)->update(['rank' => $request->index+1]);
        }else{
            $product->where('dish_type',$request->dish_type)->where('rank',$request->index)->update(['rank' => $request->index+1]);            
            $product->where('product_id',$request->product_id)->update(['rank' => $request->index]);            
        }
*/

            $product->where('dish_type',$request->dish_type)->where('product_rank',$request->index)->update(['product_rank' => $request->index+1]);

            $product->where('product_id',$request->product_id)->update(['product_rank' => $request->index]);

            $product->where('dish_type',$request->dish_type)->where('product_rank',1)->where('product_id','>',$request->product_id)->update(['product_rank' => $request->index+1]);
        
        return response()->json(['status' => 'success', 'response' => true,'data'=>"Rank Updated"]);
    }
}
