<?php

namespace App\Http\Controllers\Iframe;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\Store;
use App\Product;
use App\Company;
use App\DishType;
use App\Order;
use App\OrderDetail;
use App\Gdpr;
use App\User;
use App\PromotionLoyalty;
use App\OrderCustomerLoyalty;
use Session;
use Cookie;
use DB;
use Auth;
use App\ProductPriceList;
use Carbon\Carbon;
use Helper;

class IframeController extends Controller
{
    public function menuList(Request $request, $storeId){
        // Get store detail
        $request->session()->put('storeId', $storeId);
        $storedetails = Store::where('store_id' , $storeId)->first();

        // Get the dish_type ids have products available
        $dish_ids = array();
        $productPriceList = ProductPriceList::where('store_id',$storeId)->where('publishing_start_date','<=',Carbon::now())->where('publishing_end_date','>=',Carbon::now())->with('menuPrice')->with('storeProduct')->leftJoin('product', 'product_price_list.product_id', '=', 'product.product_id')->orderBy('product.product_rank', 'ASC')->orderBy('product.product_id')->get();

        if($productPriceList->count())
        {
            foreach ($productPriceList as $row) {
                foreach ($row->storeProduct as $storeProduct) {
                    $dish_ids[] = $storeProduct->dish_type;
                }
            }
        }

        // 
        if( !empty($dish_ids) )
        {
            $menuTypes = DishType::from('dish_type')
                ->where('u_id' , $storedetails->u_id)
                ->where('parent_id', null)
                ->where('dish_activate','1')
                ->whereIn('dish_id', array_unique($dish_ids))
                ->orderBy('rank')
                ->orderBy('dish_id')
                ->get();

            if($menuTypes->count())
            {
                // Get loyalty offer
                $promotionLoyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
                    ->select(['PL.id', 'PL.quantity_to_buy', 'PL.quantity_get', 'PL.validity', DB::raw('DATE_FORMAT(PL.end_date, "%d/%m-%Y") AS end_date'), DB::raw('GROUP_CONCAT(dish_type_id) AS dish_type_ids')])
                    ->join('promotion_loyalty_dish_type AS PLDT', 'PLDT.loyalty_id', '=', 'PL.id')
                    ->where(['PL.store_id' => $storedetails->store_id, 'PL.status' => '1'])
                    ->where('PL.start_date', '<=', Carbon::now()->format('Y-m-d h:i:00'))
                    ->where('PL.end_date', '>=', Carbon::now()->format('Y-m-d h:i:00'))
                    ->groupBy('PL.id')
                    ->first();
                
                $customerLoyalty = null;
                
                if( Auth::check() && $promotionLoyalty )
                {
                    // Get count of 'loyalty' used number of times
                    $orderCustomerLoyalty = OrderCustomerLoyalty::from('order_customer_loyalty AS OCL')
                        ->select([DB::raw('COUNT(OCL.id) AS cnt')])
                        ->join('orders', 'orders.order_id', '=', 'OCL.order_id')
                        ->where(['OCL.customer_id' => Auth::id(), 'OCL.loyalty_id' => $promotionLoyalty->id])
                        ->where('orders.online_paid', '!=', 2)
                        ->first();

                    // Check if loyalty validity is 'false' so user can use n number of times or, validity should be greater than used validity of user
                    if( (!$promotionLoyalty->validity) || ($promotionLoyalty->validity > $orderCustomerLoyalty->cnt) )
                    {
                        // Get customer loyalty
                        $customerLoyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
                            ->select(['OD.loyalty_id', DB::raw('SUM(OD.product_quality) AS quantity_bought')])
                            ->join('order_details AS OD', 'OD.loyalty_id', '=', 'PL.id')
                            ->join('orders', 'orders.order_id', '=', 'OD.order_id')
                            ->where(['PL.id' => $promotionLoyalty->id, 'OD.user_id' => Auth::id()])
                            ->where('orders.online_paid', '!=', 2)
                            ->where('OD.loyalty_id', '!=', null)
                            ->groupBy('OD.loyalty_id')
                            ->first();
                    }
                }
            }
        }

        return view('v1.user.pages-iframe.store-menu-list', compact('storedetails', 'menuTypes', 'promotionLoyalty', 'customerLoyalty', 'orderCustomerLoyalty'));
    }
}
