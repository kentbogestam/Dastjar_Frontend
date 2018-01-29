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
    	return view('order.alert-ready',compact('orderID'));
    }

    public function orderDeliver(Request $request, $orderID){
    	$message = 'orderDeliver';
    	$this->sendNotifaction($orderID , $message);
		DB::table('orders')->where('customer_order_id', $orderID)->update([
                            'paid' => 1,
                        ]);
    	return redirect()->action('AdminController@index')->with('success', 'Order Deliver Notifaction Send Successfully.');
    }

    public function deliverNotifaction(){
    	return view('order.alert-deliver');
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
    		$url = env('APP_URL').'/public/deliver-notifaction';
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
		$jsonResponse = $pushNotification->toString();
    }

}
