<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Session;
use App\Payment;
use DB;
use App\Order;
use App\OrderDetail;
use App\User;
use App\Store;

class PaymentController extends Controller
{
    //
    public function payment(Request $request)
    {
    	if(!empty($request->input())){
    		$amount = $request->session()->get('paymentAmount');
    		//dd($amount);
	    	$stripeAccount = $request->session()->get('stripeAccount');
	    	$orderId = $request->session()->get('OrderId');
	      try {
	        $token = $request->stripeToken;
	        Stripe::setApiKey('sk_test_EypGXzv2qqngDIPIkuK6aXNi');
	        $charge = Charge::create(array(
	            'amount' => $amount,
	            'currency' => 'sek',
	            'description' => 'Order charge',
	            'source' => $token
	        ), array('stripe_account' => $stripeAccount));
	        if($charge->status == "succeeded"){

	        	DB::table('orders')->where('order_id', $orderId)->update([
	                        'online_paid' => 1,
	                    ]);

	        	$paymentSave =  new Payment();
	        	$paymentSave->user_id = Auth()->id();
	        	$paymentSave->order_id = $orderId;
	        	$paymentSave->transaction_id = $charge->application;
	        	$paymentSave->amount = $charge->amount;
	        	$paymentSave->balance_transaction = $charge->balance_transaction;
	        	$paymentSave->save();

	        	$order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();
		        //dd($order->currencies);
		        $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();
		        return view('order.index', compact('order','orderDetails'))->with('success', 'Payment Done Successfully');

	        }else{

	        }
	    } catch (\Exception $ex) {
	        return $ex->getMessage();
	      }
    	}else{

    		$todayDate = $request->session()->get('browserTodayDate');
    		$currentTime = $request->session()->get('browserTodayTime');
    		$todayDay = $request->session()->get('browserTodayDay');
    		$userDetail = User::whereId(Auth()->id())->first();
            $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
            
            return view('index', compact('companydetails'));
    	}
    	
    }
}