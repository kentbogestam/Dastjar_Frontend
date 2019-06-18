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
use App\Helper;
use Session;


class PushNotifactionController extends Controller
{
    public function orderReady(Request $request, $orderID){
    	$message = 'orderReady';
    	$this->sendNotifaction($orderID , $message);
		DB::table('orders')->where('customer_order_id', $orderID)->update([
                            'ready_notifaction' => 1,
                        ]);
    	return redirect()->action('AdminController@index')->with('success', 'Order Ready Notification Send Successfully.');
		//dd($jsonResponse);
        //return view('order.alert-ready',compact('orderID'));
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

            return view('order.alert-ready',compact('orderDetail', 'orderID','companydetails','user'));            
        }else{
            return redirect('home');
        }
    }

    public function orderDeliver(Request $request, $orderID){
        /*$helper = new Helper();
        try {
        $helper->logs("Step 1: order id = " . $orderID);       

    	$message = 'orderDeliver';

        $OrderId = Order::where('customer_order_id' , $orderID)->first();

        if($OrderId->user_id != 0){
            $recipients = [];
        if($OrderId->user_type == 'customer'){
            $adminDetail = User::where('id' , $OrderId->user_id)->first();

            //$afterRemoveFirstZeroNumber = substr($adminDetail->phone_number, -9);
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
        
           $helper->logs("Step 2: recipient calculation = " . $orderID . " And browser=" .$pieces[0]);  

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
            $helper->logs("Step 3: IOS notification sent = " . $orderID . " And Result=" .$result);
        }else{
            $result = $this->sendNotifaction($orderID , $message);
            $helper->logs("Step 4: Android notification sent = " . $orderID . " And Result=" .$result);

        }
        }

		DB::table('orders')->where('customer_order_id', $orderID)->update([
                            'paid' => 1,
                        ]);
        $helper->logs("Step 5: order table updated = " . $orderID . " And user id=" . $OrderId->user_id);       
        return redirect()->action('AdminController@index');
        } catch (Exception $e) {
            $helper->logs("Step 6: Exception = " .$ex->getMessage());            
        }*/

        DB::table('orders')->where('customer_order_id', $orderID)->update([
            'paid' => 1,
        ]);
        
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
    	   return view('order.alert-deliver',compact('orderID','companydetails','user'));
        }else{
            return redirect('home');
        }
    }

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
            $messageDelever = __('messages.notificationOrderDelivered', ['order_id' => $orderID]);
    		$url = env('APP_URL').'deliver-notification/'.$orderID;
            $message = "{'alert': " ."'". $messageDelever."'" . ",'_App42Convert': true,'mutable-content': 1,'_app42RichPush': {'title': " ."'". $messageDelever."'" . ",'type':'openUrl','content':" ."'". $url."'" . "}}";

    		//$message = "{'alert':'Your Order Deliver.','badge':1,'sound':'default','Url':" ."'". $url."'" . "}";
    	}else{
    		$url = env('APP_URL').'ready-notification/'.$orderID;
            $message = "{'alert': 'Your Order Deliver.','_App42Convert': true,'mutable-content': 1,'_app42RichPush': {'title': 'Your Order Deliver.','type':'openUrl','content':" ."'". $url."'" . "}}";
    		//$message = "{'alert':'Your Order Ready.','badge':1,'sound':'default','Url':" ."'". $url."'" . "}";
    	}
    	//dd(Config::get('app.php.varname'));
    	//dd(env('APP_URL').'/ready-notification/'.$orderID);
    	//dd($request->url());
    	App42API::initialize(env('APP42_API_KEY'),env('APP42_API_SECRET')); 
		$pushNotificationService = App42API::buildPushNotificationService();
		$pushNotification = $pushNotificationService->sendPushMessageToUser($userName,$message);
        return $pushNotification;
		// if($pushNotification){
		// $jsonResponse = $pushNotification->toString();
 	//}
	}
}
