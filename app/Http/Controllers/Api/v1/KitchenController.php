<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Admin;
use DB;
use App\Order;
use App\OrderDetail;
use App\Product;
use Carbon\Carbon;
use Auth;
use App\User;

class KitchenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

   public function orderDetail($reCompanyId){
        $orderDetailscustomer = Order::select('orders.*','customer.name as name')->where(['store_id' => $reCompanyId])->where('user_type','=','customer')->where('check_deliveryDate',Carbon::now()->toDateString())->where('orders.paid', '0')->whereNotIn('orders.online_paid', [2])->leftJoin('customer','orders.user_id','=','customer.id');

        $orderDetails = Order::select('orders.*','user.fname as name')->where('orders.store_id', '=' ,$reCompanyId)->where('user_type','=','admin')->where('check_deliveryDate',Carbon::now()->toDateString())->where('orders.paid', '0')->whereNotIn('orders.online_paid', [2])->leftJoin('user','orders.user_id','=','user.id');
        
        $results = $orderDetailscustomer->union($orderDetails)->get();

        return response()->json(['status' => 'success', 'response' => true,'data'=>$results]);
    }

    public function orderStartedKitchen(Request $request, $orderID){
        DB::table('order_details')->where('id', $orderID)->update(['order_started' => 1]);
        return response()->json(['status' => 'success', 'data'=>true]);
    }

    public function onReadyAjax(Request $request, $orderID){
        DB::table('order_details')->where('id', $orderID)->update(['order_ready' => 1]);
        $userOrderId = OrderDetail::where('id' , $orderID)->first();
        $userOrderStatus = OrderDetail::where('order_id' , $userOrderId->order_id)->get();
        $readyOrderStatus = OrderDetail::where('order_id' , $userOrderId->order_id)->where('order_ready' , '1')->get();
        if(count($userOrderStatus) == count($readyOrderStatus)){

            $OrderId = Order::where('order_id' , $userOrderId->order_id)->first();
            DB::table('orders')->where('order_id', $userOrderId->order_id)->update([
                            'order_ready' => 1,
                        ]);

            $message = 'orderReady';
            if($OrderId->user_type == 'customer'){
                $adminDetail = User::where('id' , $OrderId->user_id)->first();
                $recipients = ['+'.$adminDetail->phone_number_prifix.$adminDetail->phone_number];
            }else{
                $adminDetail = Admin::where('id' , $OrderId->user_id)->first();
                $recipients = ['+'.$adminDetail->mobile_phone];
            }
            $pieces = explode(" ", $adminDetail->browser);
            if($pieces[0] == 'Safari'){
               // dd($recipients);
                $url = "https://gatewayapi.com/rest/mtsms";
                $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";
                $message = "Your order ready please click on link \n ".env('APP_URL').'ready-notifaction/'.$OrderId->customer_order_id;
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
                try{
                    $result = $this->sendNotifaction($OrderId->customer_order_id , $message); 
                    return "124";                   
                }catch(\Exception $e){
                    return $e->getMessage();
                }
            }
            return response()->json(['status' => 'success', 'data'=>'Order Ready Nofifaction Send Successfully.']);
        }

        return response()->json(['status' => 'ready', 'data'=>'Order Ready Successfully.']);
    }

    
    public function updateTextspeach($id){
        DB::table('order_details')->where('id', $id)->update([
                    'is_speak' => 1,
                ]);
         return response()->json(['status' => 'success', 'response' => true,'data'=>$id]);
    }

    public function orderSpecificOrderDetail($orderId){
         $orderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time','orders.customer_order_id','orders.online_paid')->where(['order_details.order_id' => $orderId])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->get();
        return response()->json(['status' => 'success', 'data'=>$orderDetails]);
    }

    public function cateringOrders($reCompanyId){
        $cateringorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time', 'orders.customer_order_id','orders.online_paid')->where(['order_details.store_id' => $reCompanyId])->where('order_details.order_ready','=', 1)->
            where('orders.paid','=', 0)->where('orders.check_deliveryDate',Carbon::now()->toDateString())->
            whereNotIn('orders.online_paid', [2])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->orderBy('order_details.delivery_date','ASC')->get();

/*
        $cateringorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time', 'orders.customer_order_id','orders.online_paid')->where(['order_details.store_id' => $reCompanyId])->where('order_details.delivery_date','>', Carbon::now()->toDateString())->whereNotIn('orders.online_paid', [2])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->orderBy('order_details.delivery_date','ASC')->get();
  */  
        return response()->json(['status' => 'success', 'response' => true,'data'=>$cateringorderDetails]);
    }

    public function updateProductRank(Request $request){
        $product = new Product();
/*
        if($request->index == 0){
            $product->where('dish_type',$request->dish_type)->where('rank',1)->where('product_id','>',$request->product_id)->limit(1)->update(['rank' => $request->index+1]);
            $product->where('product_id',$request->product_id)->update(['rank' => $request->index]);
        }else{
            $product->where('dish_type',$request->dish_type)->where('rank',$request->index)->update(['rank' => $request->index+1]);
            $product->where('product_id',$request->product_id)->update(['rank' => $request->index]);
            $product->where('dish_type',$request->dish_type)->where('rank',1)->where('product_id','>',$request->product_id)->limit(1)->update(['rank' => $request->index+1]);
        }
*/
            $product->where('dish_type',$request->dish_type)->where('rank',$request->index)->update(['rank' => $request->index+1]);
            $product->where('product_id',$request->product_id)->update(['rank' => $request->index]);
            $product->where('dish_type',$request->dish_type)->where('rank',1)->where('product_id','>',$request->product_id)->update(['rank' => $request->index+1]);

        return response()->json(['status' => 'success', 'response' => true,'data'=>"Rank Updated"]);
    }
}
