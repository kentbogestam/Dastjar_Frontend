<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Session;
use DB;

use App\User;
use App\Order;
use App\OrderDetail;
use App\CompanySubscriptionDetail;
use App\Payment;
use App\ApplicationFee;

use App\Helper;

use Stripe\Stripe;

class PaymentController extends Controller
{
	/**
	 * Make payment from 'cart' page using 'Stripe'
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    function confirmPayment(Request $request)
    {
    	$response = array();
    	// Check if store is open for 'eat-now'
    	$orderId = $request->order_id;
    	$order = Order::where(['order_id' => $orderId])->first();
    	$storeId = $order->store_id; //store id 
    	$paymentAmount = $order->final_order_total; //final order total
    	$deliveryTimestamp = $order->delivery_timestamp; //delivery order time stamp
    	$created_at = strtotime($order->created_at) + 86400; //convert created time to time function

		$pieces = explode(" ", $request->timeNow);
        $date=date_create($pieces[3]."-".$pieces[1]."-".$pieces[2]);
        $checkOrderDate = date_format($date,"Y-m-d");
        $orderDate = $pieces[0]." ".$pieces[1]." ".$pieces[2]." ".$pieces[3];
        $orderTime = $pieces[4];
        $new_delivery_timestamp = strtotime($checkOrderDate." ".$orderTime);

        if($order->order_type == 'eat_now'){
            $order->deliver_date = $orderDate;
            $order->deliver_time = $orderTime;
            $order->check_deliveryDate = $checkOrderDate;
            $order->delivery_timestamp = $new_delivery_timestamp;
            $order->save();
        }else{
            $timesDb = explode(":",$order->order_delivery_time);
            $secondstotal = $timesDb['0'] * 3600 + $timesDb['1'] * 60 + $timesDb['2'];
            $finaltimestamp = $order->delivery_timestamp - $secondstotal;
            if($new_delivery_timestamp >= $finaltimestamp){
                return response()->json(['status'=>'expire']);
            }
        }

    	$heartbeat = Helper::isStoreLive($storeId);

    	if( (!is_null($heartbeat) && $heartbeat < 1) || ($deliveryTimestamp > $created_at) )
    	{
	    	// Get connect a/c detail
	        $companySubscriptionDetail = CompanySubscriptionDetail::from('company_subscription_detail AS CSD')
	            ->select('CSD.stripe_user_id')
	            ->join('company AS C', 'C.company_id', '=', 'CSD.company_id')
	            ->join('store AS S', 'S.u_id', '=', 'C.u_id')
	            ->where('S.store_id', $storeId)->first();
        	
        	$stripeAccount = $companySubscriptionDetail->stripe_user_id;

	    	if( !is_null($stripeAccount) && !empty($stripeAccount) )
	    	{	
	    		$amount = $paymentAmount * 100;

	    		// Get applicationFee
	    		$application_fee = 0;
	    		$applicationFee = ApplicationFee::where(['id' => 1])->first();

	    		if($applicationFee)
	    		{
	    			$stripe_fee = (($paymentAmount * $applicationFee->stripe_fee_percent)/100) + $applicationFee->stripe_fee_fixed;
	    			$stripe_fee = number_format((float)$stripe_fee, 2, '.', '');

	    			$application_fee = ($paymentAmount * $applicationFee->application_fee)/100;
	    			$application_fee = number_format((float)$application_fee, 2, '.', '');
	    			$application_fee = ($application_fee - $stripe_fee) * 100;
	    		}

	    		// 
	    		$user = User::select(['email', 'name', 'stripe_customer_id'])
					->where('id', Auth::user()->id)
					->first();

	    		// Initilize Stripe
		    	\Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

		    	// Stripe make payment using 'Payment Instent API'
		    	$intent = null;
				try {
					if ($request->has('payment_method_id')) {
			    		// Get order detail to prepare description
			    		$orderDetails = OrderDetail::select('order_details.order_id', 'order_details.product_quality', 'product.product_name')
			    			->join('product', 'order_details.product_id', '=', 'product.product_id')
			    			->where('order_details.order_id', $orderId)
			    			->get();
			    		
			    		$description = "Order ID: {$orderId}; ";
						foreach($orderDetails as $value)
						{
							$description .= $value->product_quality . " " . $value->product_name . ", ";
						}

						$vat_total = (12*$amount)/10000;
						$description .= "Vat 12%, Vat Total ".$vat_total."kr";

						// Step 1: Storing customers
						if( is_null($user->stripe_customer_id) || $user->stripe_customer_id == '' )
						{
							$arrCustomer['name'] = $user->name;
							$arrCustomer['email'] = $user->email;

							// Attach payment
							if($request->has('isSaveCard') && $request->input('isSaveCard'))
							{
								$arrCustomer['payment_method'] = $request->input('payment_method_id');
							}

							$customer = \Stripe\Customer::create($arrCustomer);

							// Update 'stripe_customer_id' for user
			                $customerId = $customer->id;
			                User::where('id', Auth::user()->id)->update(['stripe_customer_id' => $customerId]);
						}
						else
						{
							$customerId = $user->stripe_customer_id;

							// Attach payment
							if($request->has('isSaveCard') && $request->input('isSaveCard'))
							{
								$payment_method = \Stripe\PaymentMethod::retrieve($request->input('payment_method_id'));
								$payment_method->attach(['customer' => $customerId]);
							}
						}

						// Step 2: Shared PaymentMethods for connected account
						$payment_method = \Stripe\PaymentMethod::create([
							'customer' => $customerId,
							'payment_method' => $request->input('payment_method_id'),
						], ['stripe_account' => $stripeAccount]);

						// Step 3: Creating charges
						$arrPaymentIntent = array(
							'payment_method' => $payment_method->id,
							'amount' => $amount,
							'currency' => 'sek',
							'description' => $description,
							'receipt_email' => $user->email,
							'confirmation_method' => 'manual',
							'confirm' => true,
							'capture_method' => 'manual'
						);

						// If application fee exist
						if($application_fee >= 1)
						{
							$arrPaymentIntent['application_fee_amount'] = $application_fee;
						}

						if(!$request->has('chargingSavedCard'))
						{
							$arrPaymentIntent['setup_future_usage'] = 'off_session';
						}

						$intent = \Stripe\PaymentIntent::create($arrPaymentIntent, ['stripe_account' => $stripeAccount]);
					}
					
					if ($request->has('payment_intent_id')) {
						$intent = \Stripe\PaymentIntent::retrieve($request->input('payment_intent_id'), ['stripe_account' => $stripeAccount]);
						$intent->confirm();
					}
					
					$response = $this->generatePaymentResponse($intent);

					// If 'requires_action' is 'true', send 'stripeAccount' for further authentication
					if( isset($response['requires_action']) && $response['requires_action'] )
					{
						$response['stripeAccount'] = $stripeAccount;
					}
					else
					{
						// If payment succeeded, save transaction in DB
						if( (isset($response['success']) && $response['success']) || (isset($response['requires_capture']) && $response['requires_capture']) )
						{
							DB::transaction(function () use($orderId, $request, $intent, $response, $deliveryTimestamp, $created_at, $order) {
								// if delivery time is less than created date's next day.
								if($deliveryTimestamp < $created_at){
									$is_verified = '1';
								}else{
									$is_verified = '0';
								}
								DB::table('orders')->where('order_id', $orderId)->update(['online_paid' => 1, 'is_verified' => $is_verified]);

								// Save recent payment detail in DB
								$balanceTransaction = isset($intent->charges->data[0]->balance_transaction) ? $intent->charges->data[0]->balance_transaction : null;
								
								$paymentSave =  new Payment();
					        	$paymentSave->user_id = Auth()->id();
					        	$paymentSave->order_id = $orderId;
					        	$paymentSave->transaction_id = $intent->id;
					        	$paymentSave->amount = $intent->amount;
					        	$paymentSave->balance_transaction = $balanceTransaction;
					        	// $paymentSave->status = '1';

					        	if(isset($response['requires_capture']) && $response['requires_capture'])
					        	{
					        		$paymentSave->status = '2';
					        	}

					        	$paymentSave->save();
							});
						}
					}
                    
				} catch (\Stripe\Error\Base $e) {
					# Display error on client
					$response = array('error' => $e->getMessage());
				}
	    	}
	    }
	    else
	    {
	    	$response = array('errorHeartbeat' => true, 'heartbeat' => $heartbeat);
	    }

		return response()->json($response);
    }

	/**
	 * Test payment page using 'Stripe'
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    function confirmPaymentTest(Request $request)
    {
    	$response = array();

    	$stripeAccount = 'acct_1CcVinD3Ua44GPGy';

    	if( !is_null($stripeAccount) && !empty($stripeAccount) )
    	{
    		// 
	    	\Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

	    	// 
	    	$intent = null;
			try {
				if ($request->has('payment_method_id')) {
					// Step 1: Storing customers
					$customer = \Stripe\Customer::create([
					    'email' => 'ajit.singh@ampliedtech.com',
					    'payment_method' => $request->input('payment_method_id'),
					]);

					// Step 2: Shared PaymentMethods for connected account
					$payment_method = \Stripe\PaymentMethod::create([
						'customer' => $customer->id,
						'payment_method' => $request->input('payment_method_id'),
					], ['stripe_account' => $stripeAccount]);

					// Step 3: Creating charges
					$intent = \Stripe\PaymentIntent::create([
						'payment_method' => $payment_method->id,
						'amount' => 2100,
						'currency' => 'sek',
						'description' => 'description',
						'receipt_email' => 'ajit.singh@ampliedtech.com',
						'confirmation_method' => 'manual',
						'confirm' => true,
						'setup_future_usage' => 'off_session',
					], ['stripe_account' => $stripeAccount]);
				}
				if ($request->has('payment_intent_id')) {
					$intent = \Stripe\PaymentIntent::retrieve($request->input('payment_intent_id'), ['stripe_account' => $stripeAccount]);
					$intent->confirm();
				}
				$response = $this->generatePaymentResponse($intent);

				if( isset($response['requires_action']) && $response['requires_action'] )
				{
					$response['stripeAccount'] = $stripeAccount;
				}
			} catch (\Stripe\Error\Base $e) {
				# Display error on client
				$response = array('error' => $e->getMessage());
			}
    	}

		// return response()->json($intent);
		return response()->json($response);
    }

    /**
     * Payment response following 'PaymentIntent API'
     * @param  [object] $intent
     * @return [array]
     */
    function generatePaymentResponse($intent)
    {
		# i. Note that if your API version is before 2019-02-11, 'requires_action' appears as 'requires_source_action'.
		# ii. Status 'requires_confirmation' when 'charging-saved-cards'
		if ( ($intent->status == 'requires_source_action' && $intent->next_action->type == 'use_stripe_sdk') || ($intent->status == 'requires_confirmation') ) {
			# Tell the client to handle the action
			$data = array(
				'requires_action' => true,
				'payment_intent_client_secret' => $intent->client_secret
			);
		} else if($intent->status == 'requires_capture') {
			# Payment authorized, but not yet captured
			$data = array('requires_capture' => true);
		} else if ($intent->status == 'succeeded') {
			# The payment didn’t need any additional actions and completed!
			# Handle post-payment fulfillment
			$data = array('success' => true);
		} else {
			# Invalid status
			http_response_code(500);
			$data = array('error' => 'Invalid PaymentIntent status', 'intent' => $intent);
		}

		return $data;
    }

    // Delete customer attached card
    function deleteSource(Request $request)
    {
    	$response = array();

        // Get stripe customer_id
        $user = User::select(['email', 'name', 'stripe_customer_id'])
				->where('id', Auth::user()->id)
				->first();

		if( !is_null($user->stripe_customer_id) && !$user->stripe_customer_id == '' )
        {
        	\Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        	
			$payment_method = \Stripe\PaymentMethod::retrieve($request->sourceId);
			$payment_method->detach();
        }
        else
        {
            $response['error'] = 'Attached source didn\'t match the customer.';
        }

        return response()->json($response);
    }
}
