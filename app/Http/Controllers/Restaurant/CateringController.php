<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Helper;
use App\OrderDetail;
use App\Customer;
use App\Order;
use App\User;
use Carbon\Carbon;

class CateringController extends Controller
{
    public function orderCateringOrderDetail($id)
    {
        //to get details of single order
        $items = OrderDetail::with(['productDetail'])
            ->where('order_id',$id)
            ->get();
        $output = '';
        if(!empty($items->toArray())){
            foreach($items as $item){
                $qtyFree = @$item->quantity_free == '0' ? 'No' : @$item->quantity_free;
                $output .= "<tr>";
                $output .= "<td>".@$item->productDetail->product_name."</td>";
                $output .= "<td>".@$qtyFree."</td>";
                $output .= "<td>".@$item->price."</td>";
                $output .= "<td>".@$item->product_quality."</td>";
                $output .= "<td>".(@$item->price * (@$item->product_quality - @$item->quantity_free))." SEK </td>";
                $output .= "</tr>";   
            }
        }else{      
            $output .= "<tr>";
            $output .= "<td colspan='4'> - No Order Details Found - </td>";
            $output .= "</tr>";
        }
        
        return $output;
    }
    
    public function orderCateringUserDetail($id)
    {
        //to get user details of single order
        $item = Customer::with('customerAddressDetail')->where('id',$id)->first();
        $output = '';
        if(!empty($item)){             
            $output .= "<tr>";
            $output .= "<td>".@$item->name."</td>";
            $output .= "<td>".@$item->email."</td>";
            $output .= "<td>+".@$item->phone_number_prifix." ".@$item->phone_number."</td>";
            $output .= "<td>".@$item->customerAddressDetail->street.", ".@$item->customerAddressDetail->city."<br>".__('messages.zipcode')." - ".@$item->customerAddressDetail->zipcode."</td>";
            $output .= "</tr>";
        }else{      
            $output .= "<tr>";
            $output .= "<td colspan='2'> - No User Found - </td>";
            $output .= "</tr>";
        }
        
        return $output;
    }
        
    public function cateringOrders($id)
    {
        $query = Order::with(['orderdetailDetail','customerDetail','customerFullDetail'])
            ->where('store_id',$id)
            ->where('order_type', 'eat_later')
            ->where('cancel','!=', 1)
            ->where('online_paid', '>', 0)
            ->where('delivery_timestamp', '>', time())
            ->where('is_verified', '0');

        //to get list of orders
        $items = $query->where('catering_order_status', '!=', 2)->get();
        //to get list of orders
        $count = $query->where('catering_order_status', '0')->count();

        return response()->json(['status' => true, 'data' => $items, 'count' => $count]);
    }
        
    public function orderCateringRejectAccept($id,$status)
    {
        //to accept and reject the orders
        $result = Order::where('order_id',$id)->update(['catering_order_status' => $status]);
        $order = Order::findOrFail($id);
        
        if($status == "2"){ 
            $messageDelever = __('messages.notificationOrderReceived', ['order_id' => $order->customer_order_id]);
        }else{
            $messageDelever = __('messages.notificationOrderReject', ['order_id' => $order->customer_order_id]);
        }

        if($order)
        {
            // Get customer
            $customer = User::where('id' , $order->user_id)->first();
            $browser = explode(" ", $customer->browser);

            // Check if need to send SMS/notification
            if( ($browser == 'Safari') || ( isset($customer->browser) && strpos($customer->browser, 'Mobile/') !== false ) || ( isset($customer->browser) && strpos($customer->browser, 'wv') !== false ) )
            {
                $recipients = array();
                if(isset($customer->phone_number_prifix) && isset($customer->phone_number))
                {
                    $recipients = ['+'.$customer->phone_number_prifix.$customer->phone_number];
                }

                if( !empty($recipients) )
                {
                    $url = env('APP_URL').'order-view/'.$order->order_id;
                    $message = $messageDelever."\n".$url;
                    $result = Helper::apiSendTextMessage($recipients, $message);
                }
            }
            else
            {
                $url = env('APP_URL').'order-view/'.$order->order_id;
                $message = "{'alert': '".$messageDelever."','_App42Convert': true,'mutable-content': 1,'_app42RichPush': {'title': '".$messageDelever."','type':'openUrl','content':" ."'". $url."'" . "}}";
                $result = Helper::sendNotifaction($customer->email , $message);
            }
        }
    
        if($result){
            return response()->json(['success'=>true]);   
        }else{
            return response()->json(['success'=>false]);
        }
    }

    public function cateringAutoDelete()
    {
        //auto cancellaton status updating cron job function in controller
        Order::where('order_type','eat_later')
            ->where('online_paid', '!=', '1')
            ->where('cancel', '0')
            ->where('delivery_timestamp', '>', time())
            ->where('delivery_timestamp', '<', strtotime('+12 hour'))
            ->update(['cancel' => 3]);
        Order::where('order_type','eat_later')
            ->where('online_paid','1')
            ->where('cancel', '0')
            ->where('delivery_timestamp', '>', time())
            ->where('delivery_timestamp', '<', strtotime('+12 hour'))
            ->update(['is_verified' => '1']);
    }
}
