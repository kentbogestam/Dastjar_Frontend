<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $stripeAccount = $request->session()->get('stripeAccount');

    	// if( !is_null($stripeAccount) && !empty($stripeAccount) )
    	if(1)
    	{
    		// 'Metadata'
    		$orderId = $request->session()->get('OrderId');
    		$amount = $request->session()->get('paymentAmount') * 100;
    		$emailId = User::where('id', $request->session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'))->first()->email;
    		$description = "";

    		// Get order detail to prepare description
    		$orderDetails = OrderDetail::select('order_details.order_id', 'order_details.product_quality', 'product.product_name')
    			->join('product', 'order_details.product_id', '=', 'product.product_id')
    			->where('order_details.order_id', $orderId)
    			->get();

			foreach($orderDetails as $value)
			{
				$description .= $value->product_quality . " " . $value->product_name . ", ";
			}

			$vat_total = (12*$amount)/10000;
			$description .= "Vat 12%, Vat Total ".$vat_total."kr";

    		// Initilize Stripe
	    	\Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

	    	// Stripe make payment using 'Payment Instent API'
	    	$intent = null;
			try {
				if ($request->has('payment_method_id')) {
					# Create the PaymentIntent
					$intent = \Stripe\PaymentIntent::create([
						'payment_method' => $request->input('payment_method_id'),
						'amount' => $amount,
						'currency' => 'sek',
						'description' => $description,
						'receipt_email' => $emailId,
						'confirmation_method' => 'manual',
						'confirm' => true,
					], ['stripe_account' => $stripeAccount]);
					//], ['stripe_account' => 'acct_1BUfj3ISb6cUe2dL']);
				}
				if ($request->has('payment_intent_id')) {
					$intent = \Stripe\PaymentIntent::retrieve(
						$request->input('payment_intent_id')
					);
					$intent->confirm();
				}
				$response = $this->generatePaymentResponse($intent);

				if( isset($response['success']) && $response['success'] )
				{
					DB::transaction(function () use($orderId, $intent) {
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

    	// Get subscription detail
        /*$storeId = Session::get('storeId');
        $companySubscriptionDetail = CompanySubscriptionDetail::from('company_subscription_detail AS CSD')
            ->select('CSD.stripe_user_id')
            ->join('company AS C', 'C.company_id', '=', 'CSD.company_id')
            ->join('store AS S', 'S.u_id', '=', 'C.u_id')
            ->where('S.store_id', $storeId)->first();*/
    	
    	// if(isset($companySubscriptionDetail->stripe_user_id))
    	if(1)
    	{
    		// 
	    	\Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

	    	// 
	    	$intent = null;
			try {
				if ($request->has('payment_method_id')) {
					# Create the PaymentIntent
					$intent = \Stripe\PaymentIntent::create([
						'payment_method' => $request->input('payment_method_id'),
						'amount' => 2100,
						'currency' => 'sek',
						'confirmation_method' => 'manual',
						'confirm' => true,
					], ['stripe_account' => 'acct_1BUfj3ISb6cUe2dL']);
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
		# Note that if your API version is before 2019-02-11, 'requires_action'
		# appears as 'requires_source_action'.
		if ($intent->status == 'requires_source_action' && $intent->next_action->type == 'use_stripe_sdk') {
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
			$data = array('error' => 'Invalid PaymentIntent status');
		}

		return $data;
    }
}
