<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use DB;
// use App\Payment;
// use App\Order;
// use App\OrderDetail;
// use App\OrderCustomerDiscount;
// use App\PromotionDiscount;
// use App\User;
// use App\Store;
// use App\Helper;

use App\CompanySubscriptionDetail;

use Stripe\Stripe;

class PaymentController extends Controller
{
	/**
	 * [confirmPayment description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    function confirmPayment(Request $request)
    {
    	$response = array();

    	// Get subscription detail
        $storeId = Session::get('storeId');
        $companySubscriptionDetail = CompanySubscriptionDetail::from('company_subscription_detail AS CSD')
            ->select('CSD.stripe_user_id')
            ->join('company AS C', 'C.company_id', '=', 'CSD.company_id')
            ->join('store AS S', 'S.u_id', '=', 'C.u_id')
            ->where('S.store_id', $storeId)->first();
    	
    	if(isset($companySubscriptionDetail->stripe_user_id))
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
						'amount' => 2000,
						'currency' => 'sek',
						'confirmation_method' => 'manual',
						'confirm' => true,
						// 'return_url' => url('/'),
						// 'on_behalf_of' => $companySubscriptionDetail->stripe_user_id
					]/*, [
						'stripe_account' => $companySubscriptionDetail->stripe_user_id
					]*/);
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

		return response()->json($response);
    }

    /**
     * [generatePaymentResponse description]
     * @param  [type] $intent [description]
     * @return [type]         [description]
     */
    function generatePaymentResponse($intent)
    {
		# Note that if your API version is before 2019-02-11, 'requires_action'
		# appears as 'requires_source_action'.
		if ($intent->status == 'requires_action' && $intent->next_action->type == 'use_stripe_sdk') {
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
