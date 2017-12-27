<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\ProductPriceList;
use Carbon\Carbon;
use DB;

class OrderController extends Controller
{
    //
    public function saveOrder(Request $request){
    	//$request->session()->forget('order_date');
    	//dd($request->session()->get('order_date') != null);
    	$data = $request->input();
        //dd($data['storeID']);
    	$i = 0;
    	$total_price = 0;
    	$max_time = "00:00:00";
    	$orderType;
    	$orderDate;
    	$orderTime;
    	if($request->session()->get('order_date') != null){
    		$orderType = 'eat_later';
	    	$orderDate = $request->session()->get('order_date');
	    	$orderTime = $request->session()->get('order_date');
	    	$request->session()->forget('order_date');
    	}else{
    		$orderType = 'eat_now';
	    	$orderDate = Carbon::now()->toDateString();
	    	$orderTime = Carbon::now()->toTimeString();
    	}

    	foreach ($data['product'] as $key => $value) {
            //if commant and quantity require then use condition "$value['prod_quant'] != '0' && $value['prod_desc'] != null"
    		if($value['prod_quant'] != '0'){
	    		$productTime = Product::select('preparation_Time','company_id')->whereProductId($value['id'])->first();
    			if($i == 0){
    				$order =  new Order();
					$order->user_id = Auth::id();
                    $order->store_id = $data['storeID'];
					$order->company_id = $productTime->company_id;
					$order->order_type = $orderType;
					$order->deliver_date = $orderDate;
					$order->deliver_time = $orderTime;
					$order->save();
					$orders = Order::select('*')->whereUserId(Auth::id())->orderBy('order_id', 'DESC')->first();
					$orderId = $orders->order_id;
					$i = $i+1;
    			}else{}

    			$i = 1;
	    		if($max_time < $productTime->preparation_Time){
	    			$max_time = $productTime->preparation_Time;
	    		}else{}
	    		$productPrice = ProductPriceList::select('price')->whereProductId($value['id'])->first();
	    		$total_price = $total_price + ($productPrice->price * $value['prod_quant']); 
	    		$orderDetail =  new OrderDetail();
	    		$orderDetail->order_id = $orders->order_id;
	    		$orderDetail->user_id = Auth::id();
	    		$orderDetail->product_id = $value['id'];
	    		$orderDetail->product_quality = $value['prod_quant'];
	    		$orderDetail->product_description = $value['prod_desc'];
	    		$orderDetail->price = $productPrice->price;
	    		$orderDetail->time = $productTime->preparation_Time;
	    		$orderDetail->save();
    		}
    	}

    	DB::table('orders')->where('order_id', $orderId)->update([
                    'order_delivery_time' => $max_time,
                    'order_total' => $total_price,
                ]);

    	$order = Order::select('*')->where('order_id',$orderId)->first();

    	//$orderDetails = OrderDetail::select('*')->where('order_id',$orderId)->get();
    	$orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();
    	return view('order.index', compact('order','orderDetails'));
    }

    public function orderView($orderId){
        $order = Order::select('*')->where('order_id',$orderId)->first();

        $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();
        return view('order.index', compact('order','orderDetails'));
    }
}
