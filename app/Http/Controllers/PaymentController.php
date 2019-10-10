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

use App\OrderCustomerDiscount;
use App\PromotionDiscount;

use App\User;
use App\Store;
use App\Helper;

class PaymentController extends Controller
{
    public function payment(Request $request)
    { 
    	if(!empty($request->input())){
    		$amount = $request->session()->get('paymentAmount') * 100;
	    	$stripeAccount = $request->session()->get('stripeAccount');
	    	$orderId = $request->session()->get('OrderId');

			try {
		        $token = $request->stripeToken;
				Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

		        $customer = new User();
				$emailId = $customer->where('id',$request->session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'))->first()->email;
				
				//
				$orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

				$description = "";

				foreach ($orderDetails as $key => $value) {
					# code...
					$description .= $value->product_quality . " " . $value->product_name . ", ";
				}

				$vat_total = (12*$amount)/10000;

				$description .= "Vat 12%, Vat Total " . $vat_total . "kr";

				if (filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
					$arrChargeCreate = array(
			            'amount' => $amount,
			            'currency' => 'sek',
						'description' => $description,
						// 'destination' => $stripeAccount,
		                'receipt_email' => $emailId,
			            'source' => $token
					);
				} else {
					$arrChargeCreate = array(
			            'amount' => $amount,
			            'currency' => 'sek',
						'description' => $description,
						// 'destination' => $stripeAccount,
			            'source' => $token
					);
				}

				// Make payment and error handling
				try {
					$charge = Charge::create($arrChargeCreate, array("stripe_account" => $stripeAccount));

					//
					if($charge->status == "succeeded"){
						DB::transaction(function () use($orderId, $charge) {
							DB::table('orders')->where('order_id', $orderId)->update(['online_paid' => 1]);

							$paymentSave =  new Payment();
				        	$paymentSave->user_id = Auth()->id();
				        	$paymentSave->order_id = $orderId;
				        	$paymentSave->transaction_id = $charge->application;
				        	$paymentSave->amount = $charge->amount;
				        	$paymentSave->balance_transaction = $charge->balance_transaction;
				        	$paymentSave->save();
						});

			        	$order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

				        $storeId = $order->store_id;
				        $storeDetail = Store::where('store_id' , $storeId)->first();
				        $user = User::where('id',$order->user_id)->first();

		        		$helper = new Helper();
		        		$helper->logs($order->user_id . " - browser");

		                Session::flash('success', 'Payment Done Successfully');

						// return view('order.index', compact('order','orderDetails','storeDetail','user'))->with('success', 'Payment Done Successfully');
		                return redirect()->route('order-view', $orderId);
					}
				} catch(Stripe\Error\Card $e) {
					// Since it's a decline, \Stripe\Error\Card will be caught
					$body = $e->getJsonBody();
    				$err  = $body['error'];

					return view('v1.user.pages.blank-page')->with('message',  $err['message']);
				} catch (Stripe\Error\InvalidRequest $e) {
					// Invalid parameters were supplied to Stripe's API
					return view('v1.user.pages.blank-page')->with('message',  $e->getMessage());
				} catch (Stripe\Error\Authentication $e) {
					// Authentication with Stripe's API failed
    				// (maybe you changed API keys recently)
    				return view('v1.user.pages.blank-page')->with('message',  $e->getMessage());
				} catch (Stripe\Error\ApiConnection $e) {
					// Network communication with Stripe failed
					return view('v1.user.pages.blank-page')->with('message',  $e->getMessage());
				} catch (Stripe\Error\Base $e) {
					// Display a very generic error to the user, and maybe send
    				// yourself an email
    				return view('v1.user.pages.blank-page')->with('message',  $e->getMessage());
				} catch (Exception $e) {
					// Something else happened, completely unrelated to Stripe
					return view('v1.user.pages.blank-page')->with('message',  $e->getMessage());
				}
			} catch (\Exception $ex) {
	        	return view('v1.user.pages.blank-page')->with('message', $ex->getMessage());
			}
    	}else{

    		$todayDate = $request->session()->get('browserTodayDate');
    		$currentTime = $request->session()->get('browserTodayTime');
    		$todayDay = $request->session()->get('browserTodayDay');
    		$userDetail = User::whereId(Auth()->id())->first();
            $companydetails = Store::getListRestaurants($request->session()->get('with_login_lat'),$request->session()->get('with_login_lng'),$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
            
            return view('index', compact('companydetails'));
    	}
    	
    }
}
