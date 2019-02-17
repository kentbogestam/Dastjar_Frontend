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
use App\DishType;
use App\Store;
use Carbon\Carbon;
use Auth;
use App\User;
use App\ProductPriceList;
use App\ProductOfferSloganLangList;
use App\ProductOfferSubSloganLangList;
use App\LangText;

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
        $orderDetailscustomer = Order::select('orders.*','customer.name as name')
            ->where(['orders.store_id' => $reCompanyId])
            ->where('user_type','=','customer')
            ->where('check_deliveryDate',Carbon::now()->toDateString())
            ->where('orders.paid', '0')
            ->whereNotIn('orders.online_paid', [2])
            ->where('orders.cancel','!=', 1)
            ->leftJoin('customer','orders.user_id','=','customer.id');

        $store = Store::select(['extra_prep_time', 'order_response'])->where('store_id', $reCompanyId)->first();
        $extra_prep_time = $store->extra_prep_time;
        
        // $results = $orderDetailscustomer->union($orderDetails)->get();

        $results = $orderDetailscustomer->get();

        // Get order items
        $orderItems = array();
        if($results)
        {
            $orderItems = OrderDetail::select('order_details.id', 'order_details.product_quality', 'order_details.product_description', 'product.product_name')
                ->join('orders', 'orders.order_id', '=', 'order_details.order_id')
                ->join('product', 'product.product_id', '=', 'order_details.product_id')
                ->where(['orders.store_id' => $reCompanyId, 'user_type' => 'customer', 'check_deliveryDate' => Carbon::now()->toDateString(), 'orders.order_started' => '0', 'orders.paid' => '0'])
                ->whereNotIn('orders.online_paid', [2])
                ->where('orders.cancel','!=', 1)
                ->get();
        }

        return response()->json(['status' => 'success', 'response' => true, 'store' => $store, 'extra_prep_time' => $extra_prep_time, 'data'=>$results, 'orderItems' => $orderItems]);
    }
    
    /**
     * Update order item as speak
     * @param  [int] $id [description]
     * @return [json]     [description]
     */
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
        $cateringorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.user_id','orders.deliver_date','orders.deliver_time','orders.order_delivery_time', 'orders.customer_order_id','orders.online_paid','orders.cancel')->where(['order_details.store_id' => $reCompanyId])->where('order_details.delivery_date','>', Carbon::now()->toDateString())->whereNotIn('orders.online_paid', [2])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->where('orders.cancel', '!=', 1)->orderBy('order_details.delivery_date','ASC')->get();
    
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

    /**
     * Update restaurant menu order to display
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateMenuRank(Request $request)
    {
        $dishType = new DishType();

        $dishType->where('u_id', $request->u_id)->where('rank', $request->index)->update(['rank' => ($request->index+1)]);
        $dishType->where('dish_id', $request->dish_id)->update(['rank' => $request->index]);

        return response()->json(['status' => 'success', 'response' => true,'data' => "Rank Updated"]);
    }

}
