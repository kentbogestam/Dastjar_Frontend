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
    /**
     * Select datetime to make order 'eat later'
     * @param  Request $request [description]
     * @param  [type]  $storeId [description]
     * @return [type]           [description]
     */
    function eatLaterDateTime(Request $request, $storeId)
    {
        $request->session()->put('iFrameMenu', true);

        $storedetails = Store::where('store_id' , $storeId)->first();
        
        return view('v1.user.pages-iframe.eat-later-datetime', compact('storeId', 'storedetails'));
    }

    /**
     * Post datetime 'eat later', redirect to menu
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function eatLaterDateTimePost(Request $request)
    {
        // Validation
        $this->validate($request, [
            'dateorder' => 'required',
            'store_id' => 'required'
        ]);

        $data = $request->input();
        $request->session()->put('order_date', $data['dateorder']);

        Session::forget('people_serve');
        if($data['people_serve'])
        {
            $request->session()->put('people_serve', $data['people_serve']);
        }

        return redirect('iframe/restro-menu-list/'.$data['store_id']);
    }

    /**
     * [menuList description]
     * @param  Request $request   [description]
     * @param  [type]  $storeId   [description]
     * @param  integer $styleType [description]
     * @return [type]             [description]
     */
    public function menuList(Request $request, $storeId, $styleType = 0){
        // 
        $request->session()->put('iFrameMenu', true);
        
        // Get store detail
        $request->session()->put('storeId', $storeId);
        $storedetails = Store::where('store_id' , $storeId)->first();

        // Get the dish_type ids have products available
        $dish_ids = array();

        $timeToday = date('H:i:s',strtotime(Carbon::now()));
        
        $productPriceList = ProductPriceList::select('dish_type')
            ->where('store_id',$storeId)
            // ->where('publishing_start_date','<=',Carbon::now())
            // ->where('publishing_end_date','>=',Carbon::now())
            ->where('publishing_start_time','<=',$timeToday)
            ->where('publishing_end_time','>=',$timeToday)
            ->where('dish_type', '!=', null)
            ->join('product', 'product_price_list.product_id', '=', 'product.product_id')
            ->orderBy('product.product_rank', 'ASC')
            ->orderBy('product.product_id')
            ->where('product.start_of_publishing', '<=', Carbon::now())
            ->get();

        if($productPriceList->count())
        {
            foreach ($productPriceList as $row) {
                $dish_ids[] = $row->dish_type;
            }

            $dish_ids = array_unique($dish_ids);
        }

        // 
        if( !empty($dish_ids) )
        {
            $menuTypes = array();

            $dishType = DishType::from('dish_type')
                ->where('u_id' , $storedetails->u_id)
                // ->where('parent_id', null)
                ->where('dish_activate','1')
                ->where('extras','0')
                ->whereIn('dish_id', $dish_ids)
                ->orderBy('rank')
                ->orderBy('dish_id')
                ->get();

            if($dishType)
            {
                $dishIds = array();

                foreach($dishType as $dish)
                {
                    if( !is_null($dish->parent_id) )
                    {
                        // Get 'dish id' from parent ID
                        $dishTypeLevel0 = DishType::from('dish_type AS DT1')
                            ->select(['DT1.dish_id', 'DT1.dish_name', 'DT1.dish_image', 'DT1.rank', 'DT1.extras'])
                            ->leftJoin('dish_type AS DT2', 'DT2.parent_id', '=', 'DT1.dish_id')
                            ->leftJoin('dish_type AS DT3', 'DT3.parent_id', '=', 'DT2.dish_id')
                            ->whereRaw("(DT1.dish_id = '{$dish->dish_id}' OR DT2.dish_id = '{$dish->dish_id}' OR DT3.dish_id = '{$dish->dish_id}') AND DT1.parent_id IS NULL")
                            ->groupBy('DT1.dish_id')
                            ->first();
                        
                        if($dishTypeLevel0)
                        {
                            if( !in_array($dishTypeLevel0->dish_id, $dishIds) )
                            {
                                $dishIds[] = $dishTypeLevel0->dish_id;

                                $menuTypes[] = (object) array(
                                    'dish_id' => $dishTypeLevel0->dish_id,
                                    'dish_name' => $dishTypeLevel0->dish_name,
                                    'dish_image' => $dishTypeLevel0->dish_image,
                                    'rank' => $dishTypeLevel0->rank,
                                    'extras' => $dish->extras,
                                );
                            }
                        }
                    }
                    else
                    {
                        if( !in_array($dish->dish_id, $dishIds) )
                        {
                            $dishIds[] = $dish->dish_id;

                            $menuTypes[] = (object) array(
                                'dish_id' => $dish->dish_id,
                                'dish_name' => $dish->dish_name,
                                'dish_image' => $dish->dish_image,
                                'rank' => $dish->rank,
                                'extras' => $dish->extras,
                            );
                        }
                    }
                }

                // Sort the category
                usort($menuTypes, function($a, $b) {
                    return $a->rank <=> $b->rank;
                });
            }

            if( !empty($menuTypes) )
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

        // dd($menuTypes);
        return view('v1.user.pages.store-menu-list', compact('storedetails', 'menuTypes', 'promotionLoyalty', 'customerLoyalty', 'orderCustomerLoyalty', 'styleType'));
    }
}
