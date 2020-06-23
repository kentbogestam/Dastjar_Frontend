<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

use App\Order;
use App\User;
use App\Admin;
use DB;
use App\Store;
use App\OrderDetail;
use App\CompanySubscriptionDetail;
use App\Payment;

use App\Helper;
use Session;

use Stripe\Stripe;


class PushNotifactionController extends Controller
{
    public function orderReady(Request $request, $orderID){
        $message = 'orderReady';
        $this->sendNotifaction($orderID , $message);
        DB::table('orders')->where('customer_order_id', $orderID)->update([
                            'ready_notifaction' => 1,
                        ]);
    	return redirect()->action('AdminController@index')->with('success', 'Order Ready Notification Send Successfully.');
    }

    public function readyNotifaction(Request $request, $orderID){
        if(Order::where(['customer_order_id' => $orderID, 'order_ready' => 1])->exists()){
            $orderDetail = Order::where('customer_order_id', $orderID)->first();

            if(!User::where('id', $orderDetail->user_id)->exists()){
                return redirect('home');
            }

            $user = User::where('id', $orderDetail->user_id)->first();
            $companydetails = Store::where('store_id', $orderDetail->store_id)->first();

            // Remove order from session 'recentOrderList' once its ready
            if( Session::has('recentOrderList.'.$orderDetail->order_id) )
            {
                Session::forget('recentOrderList.'.$orderDetail->order_id);
            }

            // return view('order.alert-ready',compact('orderDetail', 'orderID','companydetails','user'));            
            return view('v1.user.pages.order-ready',compact('orderDetail', 'orderID','companydetails','user'));
        }else{
            return redirect('home');
        }
    }

    public function orderDeliver(Request $request, $orderID){
        // Check if order exist
        $order = Order::select(['user_id', 'user_type', 'order_id', 'store_id', 'delivery_type'])
            ->where('customer_order_id', $orderID)
            ->first();

        if($order)
        {
            $isDelivered = 0;

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

            // Check if order can get delivered
            if($isDelivered)
            {
                // Update order as delivered
                DB::table('orders')->where('customer_order_id', $orderID)->update([
                    'paid' => 1,
                ]);

                // 
                if($order->delivery_type == '3')
                {
                    $helper = new Helper();
                    try {
                        $message = 'orderDeliver';
                        
                        if($order->user_id != 0){ 
                            $recipients = [];
                            if($order->user_type == 'customer'){
                                $adminDetail = User::where('id' , $order->user_id)->first();

                                if(isset($adminDetail->phone_number_prifix) && isset($adminDetail->phone_number)){
                                    $recipients = ['+'.$adminDetail->phone_number_prifix.$adminDetail->phone_number];   
                                }
                            }else{
                                $adminDetail = Admin::where('id' , $order->user_id)->first();
                                $recipients = ['+'.$adminDetail->mobile_phone];
                            }

                            if(isset($adminDetail->browser)){
                                $pieces = explode(" ", $adminDetail->browser);
                            }else{
                                $pieces[0] = '';                              
                            }

                            if($pieces[0] == 'Safari'){
                                //dd($recipients);
                                $url = "https://gatewayapi.com/rest/mtsms";
                                $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";

                                $message = "Your order deliver please click on link \n".env('APP_URL').'deliver-notification/'.$orderID;

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
                            }else{
                                $result = $this->sendNotifaction($orderID , $message);
                            }
                        }
                    } catch (Exception $e) {}
                }
            }
        }
        
        // 
        return redirect()->action('AdminController@index');
    }

    public function deliverNotifaction(Request $request, $orderID){
        if(Order::where('customer_order_id', $orderID)->exists()){
            $orderDetail = Order::where('customer_order_id', $orderID)->first();

            if(!User::where('id', $orderDetail->user_id)->exists()){
                return redirect('home');
            }

            $user = User::where('id', $orderDetail->user_id)->first();
            $companydetails = Store::where('store_id', $orderDetail->store_id)->first();
            return view('v1.user.pages.order-delivered',compact('orderID','companydetails','user'));
        }else{
            return redirect('home');
        }
    }
  
    /**
     * Send text message to recipients using API
     * @return [type] [description]
     */
    public function sendNotifaction($orderID, $message){
        $order = Order::select('*')->where('customer_order_id',$orderID)->first();
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
                $messageDelever = __('messages.notificationOrderDelivered', ['order_id' => $orderID]);
            }else{
                $messageDelever = __('messages.orderDeliveryAtDoor', ['order_id' => $orderID]);
            }
    		$url = env('APP_URL').'deliver-notification/'.$orderID;
            $message = "{'alert': " ."'". $messageDelever."'" . ",'_App42Convert': true,'mutable-content': 1,'_app42RichPush': {'title': " ."'". $messageDelever."'" . ",'type':'openUrl','content':" ."'". $url."'" . "}}";
    	}else{
    		$url = env('APP_URL').'ready-notification/'.$orderID;
            $message = "{'alert': 'Your Order Deliver.','_App42Convert': true,'mutable-content': 1,'_app42RichPush': {'title': 'Your Order Deliver.','type':'openUrl','content':" ."'". $url."'" . "}}";
    	}
    	App42API::initialize(env('APP42_API_KEY'),env('APP42_API_SECRET')); 
		$pushNotificationService = App42API::buildPushNotificationService();
		$pushNotification = $pushNotificationService->sendPushMessageToUser($userName,$message);
        return $pushNotification;
	}
}
