<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Session;
use App\Helper;
use App\Store;
use App\PromotionDiscount;
use App\CustomerDiscount;

use Carbon\Carbon;

class PromotionController extends Controller
{
    /**
     * [applyUserDiscount description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
	function applyUserDiscount(Request $request)
    {
        $status = 0;

        // 
        $storeId = $request->storeId;
        $discountCode = $request->discountCode;
        $todayDate = Carbon::now()->format('Y-m-d h:i:00');

        // Check if discount exist
        $discount = PromotionDiscount::select(['id', 'store_id'])
            ->where(['store_id' => $storeId, 'code' => $discountCode, 'status' => '1'])
            ->where('start_date', '<=', $todayDate)
            ->where('end_date', '>=', $todayDate)
            ->first();

        if($discount)
        {
            if(Auth::check())
            {
                // Check if discount is not already applied
                if(!CustomerDiscount::where(['customer_id' => Auth::id(), 'discount_id' => $discount->id, 'status' => '1'])->first())
                {
                    $status = 1;

                    // Check if discount code belongs to the same restaurant user has already added discount for
                    $customerDiscount = CustomerDiscount::from('customer_discount AS CD')
                        ->select(['CD.id', 'PD.store_id'])
                        ->join('promotion_discount AS PD', 'CD.discount_id', '=', 'PD.id')
                        ->where(['CD.customer_id' => Auth::id(), 'CD.status' => '1', 'PD.store_id' => $discount->store_id])
                        ->first();

                    // Delete if discount already exists and replace discount cookie with new discount value
                    if($customerDiscount)
                    {
                        // Delete discount
                        CustomerDiscount::where(['id' => $customerDiscount->id])->update(['status' => '2']);

                        // Add new customer discount
                        CustomerDiscount::create(['customer_id' => Auth::id(), 'discount_id' => $discount->id]);
                    }
                    else
                    {
                        // Add new customer discount
                        CustomerDiscount::create(['customer_id' => Auth::id(), 'discount_id' => $discount->id]);
                    }

                    return redirect('restro-menu-list/'.$storeId)
                        ->with(['class' => 'success', 'msg' => __('messages.discountAddedSuccessfully')]);
                }
                else
                {
                    $status = 2;

                    return redirect('restro-menu-list/'.$storeId)
                        ->with(['class' => 'warning', 'msg' => __('messages.discountAlreadyApplied')]);
                }
            }
            else
            {
                return redirect('login');
            }
        }

        // return view('apply-user-discount', compact('status'));
        return view('v1.user.pages.apply-user-discount', compact('status'));
    }
}
