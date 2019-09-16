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

    	// Get connect a/c detail
        if( $request->session()->has('stripeAccount') )
        {
        	$stripeAccount = $request->session()->get('stripeAccount');
        }
        else
        {
	        $storeId = Session::get('storeId');
	        $companySubscriptionDetail = CompanySubscriptionDetail::from('company_subscription_detail AS CSD')
	            ->select('CSD.stripe_user_id')
	            ->join('company AS C', 'C.company_id', '=', 'CSD.company_id')
	            ->join('store AS S', 'S.u_id', '=', 'C.u_id')
	            ->where('S.store_id', $storeId)->first();
        	
        	$stripeAccount = $companySubscriptionDetail->stripe_user_id;
        }

    	if( !is_null($stripeAccount) && !empty($stripeAccount) )
    	{	
    		// Session data
    		$orderId = $request->session()->get('OrderId');
    		$amount = $request->session()->get('paymentAmount') * 100;

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
		    		
		    		$description = "";
					foreach($orderDetails as $value)
					{
						$description .= $value->product_quality . " " . $value->product_name . ", ";
					}

					$vat_total = (12*$amount)/10000;
					$description .= "Vat 12%, Vat Total ".$vat_total."kr";

					# Create the PaymentIntent
					if($request->has('chargingSavedCard') && $request->input('chargingSavedCard'))
					{
						$arrPaymentIntent = array(
							'payment_method' => $request->input('payment_method_id'),
							'customer' => $user->stripe_customer_id,
							'amount' => $amount,
							'currency' => 'sek',
							'description' => $description,
							'receipt_email' => $user->email,
							'confirmation_method' => 'manual',
							'confirm' => true,
						);
					}
					else
					{
						$arrPaymentIntent = array(
							'payment_method' => $request->input('payment_method_id'),
							'amount' => $amount,
							'currency' => 'sek',
							'description' => $description,
							'receipt_email' => $user->email,
							'confirmation_method' => 'manual',
							'confirm' => true,
							'setup_future_usage' => 'on_session',
						);
					}

					$intent = \Stripe\PaymentIntent::create($arrPaymentIntent, ['stripe_account' => $stripeAccount]);
					// $intent = \Stripe\PaymentIntent::create($arrPaymentIntent, ['stripe_account' => 'acct_1CZMp0DLCQiTSrbX']);
					// $intent = \Stripe\PaymentIntent::create($arrPaymentIntent, ['stripe_account' => 'acct_1BUfj3ISb6cUe2dL']);
				}
				if ($request->has('payment_intent_id')) {
					$intent = \Stripe\PaymentIntent::retrieve(
						$request->input('payment_intent_id')
					);
					$intent->confirm();
				}
				$response = $this->generatePaymentResponse($intent);

				// If payment succeeded, save transaction in DB
				if( isset($response['success']) && $response['success'] )
				{
					DB::transaction(function () use($orderId, $request, $user, $intent) {
						// Update order as paid
						DB::table('orders')->where('order_id', $orderId)->update(['online_paid' => 1]);

						// Save recent payment detail in DB
						$balanceTransaction = isset($intent->charges->data[0]->balance_transaction) ? $intent->charges->data[0]->balance_transaction : null;
						
						$paymentSave =  new Payment();
			        	$paymentSave->user_id = Auth()->id();
			        	$paymentSave->order_id = $orderId;
			        	$paymentSave->transaction_id = $intent->id;
			        	$paymentSave->amount = $intent->amount;
			        	$paymentSave->balance_transaction = $balanceTransaction;
			        	$paymentSave->save();

			        	// Add customer to payment; And attach card to customer
			        	if(!$request->has('chargingSavedCard'))
			        	{
			        		// Create customer and assign to payment
			        		if( is_null($user->stripe_customer_id) || $user->stripe_customer_id == '' )
							{
								$customer = \Stripe\Customer::create(array(
									'name' => $user->name,
				                    'email' => $user->email
				                ));

				                // Update 'stripe_customer_id' for user
				                $customerId = $customer->id;
				                User::where('id', Auth::user()->id)->update(['stripe_customer_id' => $customerId]);
							}
							else
							{
								$customerId = $user->stripe_customer_id;
							}

							\Stripe\PaymentIntent::update($intent->id, ['customer' => $customerId]);

							// Attach the PaymentMethod to a Customer after success
				        	if($request->has('isSaveCard') && $request->input('isSaveCard'))
							{
								$payment_method = \Stripe\PaymentMethod::retrieve($intent->payment_method);
								$payment_method->attach(['customer' => $customerId]);
							}
			        	}
					});
				}
			} catch (\Stripe\Error\Base $e) {
				# Display error on client
				$response = array('error' => $e->getMessage());
			}
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

    	if(1)
    	{
    		// 
	    	\Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

	    	// 
	    	$intent = null;
			try {
				if ($request->has('payment_method_id')) {
					$customer = \Stripe\Customer::create([
					    'email' => 'ajit.singh@ampliedtech.com',
					    'payment_method' => $request->has('payment_method_id'),
					]);

					$payment_method = \Stripe\PaymentMethod::create([
						'customer' => $customer->id,
						'payment_method' => $request->input('payment_method_id'),
					], ['stripe_account' => 'acct_1CZMp0DLCQiTSrbX']);

					# Create the PaymentIntent
					$intent = \Stripe\PaymentIntent::create([
						'payment_method' => $payment_method->id,
						'amount' => 2100,
						'currency' => 'sek',
						'description' => 'description',
						'receipt_email' => 'ajit.singh@ampliedtech.com',
						'confirmation_method' => 'manual',
						'confirm' => true,
						'setup_future_usage' => 'off_session',
					], ['stripe_account' => 'acct_1CZMp0DLCQiTSrbX']);
				}
				if ($request->has('payment_intent_id')) {
					$intent = \Stripe\PaymentIntent::retrieve(
						$request->input('payment_intent_id')
					);
					$intent->confirm();
				}
				$response = $this->generatePaymentResponse($intent);
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
}
