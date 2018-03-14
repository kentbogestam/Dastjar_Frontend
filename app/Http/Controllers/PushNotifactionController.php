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



class PushNotifactionController extends Controller
{
    public function orderReady(Request $request, $orderID){
    	$message = 'orderReady';
    	$this->sendNotifaction($orderID , $message);
		DB::table('orders')->where('customer_order_id', $orderID)->update([
                            'ready_notifaction' => 1,
                        ]);
    	return redirect()->action('AdminController@index')->with('success', 'Order Ready Notifaction Send Successfully.');
		//dd($jsonResponse);
        //return view('order.alert-ready',compact('orderID'));
    }

    public function readyNotifaction(Request $request, $orderID){
        $orderDetail = Order::where('customer_order_id', $orderID)->first();
        $companydetails = Store::where('store_id', $orderDetail->store_id)->first();
    	return view('order.alert-ready',compact('orderID','companydetails'));
    }

    public function orderDeliver(Request $request, $orderID){
    	$message = 'orderDeliver';

        $OrderId = Order::where('customer_order_id' , $orderID)->first();
        if($OrderId->user_type == 'customer'){
            $adminDetail = User::where('id' , $OrderId->user_id)->first();
            $recipients = ['+'.$adminDetail->phone_number_prifix.$adminDetail->phone_number];
        }else{
            $adminDetail = Admin::where('id' , $OrderId->user_id)->first();
            $recipients = ['+'.$adminDetail->mobile_phone];
        }
        $pieces = explode(" ", $adminDetail->browser);
        if($pieces[0] == 'Safari'){
            //dd($recipients);
            $url = "https://gatewayapi.com/rest/mtsms";
            $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";
            $message = env('APP_URL').'/public/deliver-notifaction/'.$orderID;
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
            $this->sendNotifaction($orderID , $message);
        }
		DB::table('orders')->where('customer_order_id', $orderID)->update([
                            'paid' => 1,
                        ]);
    	return redirect()->action('AdminController@index')->with('success', 'Order Deliver Notifaction Send Successfully.');
    }

    public function deliverNotifaction(Request $request, $orderID){
        $orderDetail = Order::where('customer_order_id', $orderID)->first();
        $companydetails = Store::where('store_id', $orderDetail->store_id)->first();
    	return view('order.alert-deliver',compact('orderID','companydetails'));
    }

    public function sendNotifaction($orderID, $message){
    	$order = Order::select('*')->where('customer_order_id',$orderID)->first();
    	if($order->user_type == 'customer'){
    		$userDetail = User::whereId($order->user_id)->first();
    		$userName =$userDetail->email;
    	}else{
    		$userDetail = Admin::whereId($order->user_id)->first();
    		$userName =$userDetail->email;
    	}
	
    	if($message == 'orderDeliver'){
    		$url = env('APP_URL').'/public/deliver-notifaction/'.$orderID;
    		$message = "{'alert':'Your Order Deliver.','badge':1,'sound':'default','Url':" ."'". $url."'" . "}";
    	}else{
    		$url = env('APP_URL').'/public/ready-notifaction/'.$orderID;
    		$message = "{'alert':'Your Order Ready.','badge':1,'sound':'default','Url':" ."'". $url."'" . "}";
    	}
    	//dd(Config::get('app.php.varname'));
    	//dd(env('APP_URL').'/ready-notifaction/'.$orderID);
    	//dd($request->url());
    	App42API::initialize("cc9334430f14aa90c623aaa1dc4fa404d1cfc8194ab2fd144693ade8a9d1e1f2","297b31b7c66e206b39598260e6bab88e701ed4fa891f8995be87f786053e9946"); 
		$pushNotificationService = App42API::buildPushNotificationService();
		$pushNotification = $pushNotificationService->sendPushMessageToUser($userName,$message);
		if($pushNotification){
		$jsonResponse = $pushNotification->toString();
 	}
	}
}
