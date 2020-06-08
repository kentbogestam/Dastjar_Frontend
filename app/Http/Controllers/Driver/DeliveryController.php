<?php

namespace App\Http\Controllers\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

use App\User;
use App\Admin;
use App\Driver;
use App\Company;
use App\Order;
use App\OrderDetail;
use App\OrderDelivery;
use App\UserAddress;
use App\CompanySubscriptionDetail;
use App\Payment;

use App\Helper;

use App\App42\PushNotificationService;
use App\App42\DeviceType;
use App\App42\App42Log;
use App\App42\App42Exception;
use App\App42\App42NotFoundException;
use App\App42\App42BadParameterException;
use App\App42\StorageService;
use App\App42\QueryBuilder;
use App\App42\Query;
use App\App42\App42API;
use App\App42\Util;

use Log;

class DeliveryController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:driver');
    }

    /*public function index()
    {
    	return redirect('driver/list-delivery');
    }*/

	/**
	 * Show list of delivery to driver
	 * @return [type] [description]
	 */
	public function delivery()
	{
		$companyId = Auth::guard('driver')->user()->company_id;
		$company = Company::select(['company_name'])
			->where(['company_id' => $companyId])
			// ->where('c_activ', '!=', null)
			->first();
		
		return view('driver.delivery', compact('orderDelivery', 'company'));
	}

	/**
	 * Get order list to deliver
	 * @return [type] [description]
	 */
	function getDeliverOrderList()
	{
		$driverId = Auth::guard('driver')->user()->id;
		$dateTime = date('Y-m-d H:i:s', strtotime('-5 minutes'));

		// Get deliver order list
		$orderDelivery = OrderDelivery::from('order_delivery AS OD')
			->select(['OD.id', 'O.order_id', 'O.delivery_type', 'O.delivery_at_door', 'O.customer_order_id', 'O.online_paid', 'O.deliver_time', 'O.order_delivery_time', 'O.order_response', 'O.extra_prep_time AS o_extra_prep_time', 'O.paid', 'CA.full_name', 'CA.mobile', 'CA.entry_code', 'CA.apt_no', 'CA.company_name', 'CA.other_info', 'CA.address', 'CA.street', 'CA.city', DB::raw('CONCAT(CA.street, ", ", CA.city, ", ", CA.zipcode, ", ", CA.country) AS customer_address'), 'S.store_name', 'S.phone', 'S.extra_prep_time', 'S.buffer_time', DB::raw('CONCAT(S.street, ", ", S.city, ", ", S.zip, ", ", S.country) AS store_address')])
			->join('orders AS O', 'O.order_id', '=', 'OD.order_id')
			->join('customer_addresses AS CA', 'CA.id', '=', 'O.user_address_id')
			->join('store AS S', 'S.store_id', '=', 'O.store_id')
			->where(['OD.driver_id' => $driverId, 'OD.status' => '2'])
			->where(function($query) use ($dateTime) {
				$query->where('O.paid', 0)->orWhere('O.updated_at', '>=', $dateTime);
			})
			->get();

		return response()->json(['orderDelivery' => $orderDelivery]);
	}

	/**
	 * Show driving direction to delivery
	 * @return [type] [description]
	 */
	function deliveryDirection($orderId)
	{
		$driverId = Auth::guard('driver')->user()->id;

		// Get order
		$order = Order::select(['user_address_id'])
			->where(['order_id' => $orderId])
			->first();
		
		// 
		$driver = Driver::select(['latitude', 'longitude'])
			->where(['id' => $driverId])
			->first();
		
		$markerArray = array();
		$markerArray[] = array('lat' => $driver->latitude, 'lng' => $driver->longitude);
		// $markerArray[] = array('lat' => 59.3150, 'lng' => 17.9999);

		$userAddress = UserAddress::from('customer_addresses AS CA')
			->select([DB::raw('CONCAT(CA.street, ", ", CA.city, ", ", CA.zipcode, ", ", CA.country) AS customer_address')])
			->where('id' , $order->user_address_id)
			->first();
		$address = Helper::getCoordinates($userAddress->customer_address);

		if($address)
		{
			$markerArray[] = $address;
		}

		// Encode array
        if(!empty($markerArray))
        {
            $markerArray = json_encode($markerArray);
        }
		// dd($markerArray);
		return view('driver.pickup-direction', compact('markerArray'));
	}

	/**
     * [Update order payment manually from 'Orders' page]
     * @param  [type] $order_id [primary key of table 'order']
     * @return [type]           [status]
     */
    function orderPayManually($orderId)
    {
        $status = false;
        $order = Order::findOrFail($orderId);

        if( $order->where('order_id', $orderId)->update(['online_paid' => 3]) )
        {
           $status = true;
        }

        return response()->json(['status' => $status, 'order' => $order]);
    }

	/**
	 * Mark order delivered
	 * @param  [type] $orderId [description]
	 * @return [type]          [description]
	 */
	function orderDeliver($orderId)
	{
		// Get order deliver status
		$order = Order::select(['order_id', 'store_id', 'paid', 'updated_at'])
			->where(['customer_order_id' => $orderId])
			->first();

		if($order)
		{
			// Check driver engage status
			$driverId = Auth::guard('driver')->user()->id;
			$paid = 1;
			$isDelivered = 0;

			// If order not delivered, capture the payment and deliver it, else undo delivered order within 5 min
			if($order->paid)
			{
				$dateTime = date('Y-m-d H:i:s');
				$dateTime = new \DateTime($dateTime);
				$dateDiff = $dateTime->diff(new \DateTime($order->updated_at));

				if($dateDiff->i <= 5)
				{
					$paid = 0;
				}
				else
				{
					return \Redirect::back()->with(['error' => 'You can\'t undo order after 5 minutes!' ]);
				}
			}
			else
			{
				// Get the payment if exist and not captured
	            $payment = Payment::select(['transaction_id', 'status'])
	                ->where(['order_id' => $order->order_id])->first();

	            if($payment)
	            {
					if($payment->status != '0')
					{
						// Get the Stripe Account
	                    $companySubscriptionDetail = CompanySubscriptionDetail::from('company_subscription_detail AS CSD')
	                        ->select('CSD.stripe_user_id')
	                        ->join('company AS C', 'C.company_id', '=', 'CSD.company_id')
	                        ->join('store AS S', 'S.u_id', '=', 'C.u_id')
	                        ->where('S.store_id', $order->store_id)->first();

	                    if($companySubscriptionDetail)
	                    {
	                    	$stripeAccount = $companySubscriptionDetail->stripe_user_id;

	                        // Initilize Stripe
	                        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

	                        try {
	                            // capture-later
	                            $payment_intent = \Stripe\PaymentIntent::retrieve($payment->transaction_id, ['stripe_account' => $stripeAccount]);

	                            if($payment_intent->status == 'requires_capture')
	                            {
	                                $payment_intent->capture();
	                            }

	                            if($payment_intent->status == 'succeeded')
	                            {
	                                $isDelivered = 1;

	                                // Update payment as captured
	                                Payment::where(['order_id' => $order->order_id])
	                                    ->update(['status' => '1']);
	                            }
	                        } catch (\Stripe\Error\Base $e) {
	                            # Display error on client
	                            $response = array('error' => $e->getMessage());
	                            return \Redirect::back()->with($response);
	                        }
	                    }
					}
	            }
	            else
	            {
	            	$isDelivered = 1;
	            }
			}

            // 
            if($isDelivered || $paid == 0)
            {
            	// Update order deliver status
				if(Order::where(['customer_order_id' => $orderId])->update(['paid' => $paid]))
				{
					// Check and update driver engage status
					$driverId = Auth::guard('driver')->user()->id;

					$orderDelivery = OrderDelivery::from('order_delivery AS OD')
						->join('orders AS O', 'O.order_id', '=', 'OD.order_id')
						->where(['OD.driver_id' => $driverId, 'OD.status' => '2', 'paid' => 0])
						->count();

					if($orderDelivery == 0)
					{
						Driver::where(['id' => $driverId])->update(['is_engaged' => '0']);
					}
				}
            }
		}
        
        $OrderId = Order::where('customer_order_id' , $orderId)->first();
        if($OrderId->delivery_type == '3' && $paid == '1'){
            $helper = new Helper();
            try {
            $helper->logs("Step 1: order id = " . $OrderId->order_id);       

            $message = 'orderDeliver';
            
            if($OrderId->user_id != 0){ 
                $recipients = [];
                if($OrderId->user_type == 'customer'){
                    $adminDetail = User::where('id' , $OrderId->user_id)->first();

                    if(isset($adminDetail->phone_number_prifix) && isset($adminDetail->phone_number)){
                        $recipients = ['+'.$adminDetail->phone_number_prifix.$adminDetail->phone_number];   
                    }
                }else{
                    $adminDetail = Admin::where('id' , $OrderId->user_id)->first();
                    $recipients = ['+'.$adminDetail->mobile_phone];
                }

                if(isset($adminDetail->browser)){
                    $pieces = explode(" ", $adminDetail->browser);
                }else{
                    $pieces[0] = '';                              
                }

                $helper->logs("Step 2: recipient calculation = " . $orderId . " And browser=" .$pieces[0]);  

                if($pieces[0] == 'Safari'){
                    //dd($recipients);
                    $url = "https://gatewayapi.com/rest/mtsms";
                    $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";

                    $message = "Your order deliver please click on link \n".env('APP_URL').'deliver-notification/'.$orderId;

                    $json = [
                        'sender' => 'Dastjar',
                        'message' => ''.$message.'',
                        'recipients' => [],
                    ];
                    foreach ($recipients as $msisdn) {
                        $json['recipients'][] = ['msisdn' => $msisdn];}

                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL, $url);
                    curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                    curl_setopt($ch,CURLOPT_USERPWD, $api_token.":");
                    curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($json));
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec($ch);
                    curl_close($ch);   
                    $helper->logs("Step 3: IOS notification sent = " . $orderId . " And Result=" .$result);
                }else{
                    $result = $this->sendNotifaction($orderId , $message);
                    $helper->logs("Step 4: Android notification sent = " . $orderId . " And Result=" .$result);

                }
            }
            
            $helper->logs("Step 5: order table updated = " . $orderId . " And user id=" . $OrderId->user_id);       
            } catch (Exception $e) {
                $helper->logs("Step 6: Exception = " .$ex->getMessage());           
            }
        }
        
        return redirect()->back();
	}
    
    /**
     * Send text message to recipients using API
     * @return [type] [description]
     */
    public function sendNotifaction($orderId, $message){
    	$order = Order::select('*')->where('customer_order_id',$orderId)->first();
    	if($order->user_type == 'customer'){
    		$userDetail = User::whereId($order->user_id)->first();
    	}else{
    		$userDetail = Admin::whereId($order->user_id)->first();
    	}

        if(!isset($userDetail->email)){
            return "Notification Send Successfully";
        }
        $userName = $userDetail->email;
	
    	if($message == 'orderDeliver'){
            if($order->delivery_at_door == '0'){
                $messageDelever = __('messages.notificationOrderDelivered', ['order_id' => $orderId]);
            }else{
                $messageDelever = __('messages.orderDeliveryAtDoor', ['order_id' => $orderId]);
            }
    		$url = env('APP_URL').'deliver-notification/'.$orderId;
            $message = "{'alert': " ."'". $messageDelever."'" . ",'_App42Convert': true,'mutable-content': 1,'_app42RichPush': {'title': " ."'". $messageDelever."'" . ",'type':'openUrl','content':" ."'". $url."'" . "}}";
    	}else{
    		$url = env('APP_URL').'ready-notification/'.$orderId;
            $message = "{'alert': 'Your Order Deliver.','_App42Convert': true,'mutable-content': 1,'_app42RichPush': {'title': 'Your Order Deliver.','type':'openUrl','content':" ."'". $url."'" . "}}";
    	}
    	App42API::initialize(env('APP42_API_KEY'),env('APP42_API_SECRET')); 
		$pushNotificationService = App42API::buildPushNotificationService();
		$pushNotification = $pushNotificationService->sendPushMessageToUser($userName,$message);
        return $pushNotification;
	}
}
