<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Session;
use App\Helper;
use DB;
use App\Store;
use App\PromotionLoyalty;
use App\PromotionLoyaltyDishType;
use App\DishType;

class LoyaltyController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

	/**
	 * Display listing of resource
	 * 
	 * @return [type] [description]
	 */
    public function index()
    {
    	// Get store
    	$store = Store::select(['store_id', 'store_name'])->where(['u_id' => Auth::user()->u_id])->get();

        // Get dish type
        $dishType = DishType::select(['dish_id', 'dish_lang', 'dish_name'])
            ->where(['u_id' => Auth::user()->u_id, 'dish_activate' => 1])
            ->orderBy('rank')
            ->get();

        // Get loyalty
        $loyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
            ->select(['PL.id', 'PL.quantity_to_buy', 'PL.quantity_get', 'PL.validity', 'PL.start_date', 'PL.end_date', 'S.store_name', DB::raw('GROUP_CONCAT(DISTINCT DT.dish_name) AS dish_name'), DB::raw('COUNT(DISTINCT OD.loyalty_id) AS isLoyaltyUsed')])
            ->join('store AS S', 'PL.store_id', '=', 'S.store_id')
            ->join('promotion_loyalty_dish_type AS PLDT', 'PLDT.loyalty_id', '=', 'PL.id')
            ->join('dish_type AS DT', 'PLDT.dish_type_id', '=', 'DT.dish_id')
            ->leftJoin('order_details AS OD', 'OD.loyalty_id', '=', 'PL.id')
            ->where(['PL.status' => '1', 'S.u_id' => Auth::user()->u_id, 'S.s_activ' => '1', 'DT.dish_activate' => 1])
            ->groupBy('PL.id')
            ->get();

    	return view('kitchen.loyalty.index', compact('loyalty', 'store', 'dishType'));
    }

    /**
     * Create discount
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function store(Request $request)
    {
    	// Validation
		$this->validate($request, [
			'store_id' => 'required',
			'quantity_to_buy' => 'required|numeric',
			'quantity_get' => 'required|numeric|lt:quantity_to_buy',
            'validity' => 'required|numeric',
			'start_date_utc' => 'required',
			'end_date_utc' => 'required|after:start_date_utc',
		], [
            'end_date_utc.after' => __('messages.discountDateAfter'),
        ]);

		$data = $request->only(['store_id', 'quantity_to_buy', 'quantity_get', 'validity']);
		$data['start_date'] = \DateTime::createFromFormat('Y/m/d H:i', $request->start_date_utc);
        $data['end_date'] = \DateTime::createFromFormat('Y/m/d H:i', $request->end_date_utc);

        // Check if loyalty already exist
        if(PromotionLoyalty::where(['store_id' => $data['store_id'], 'status' => '1'])->where('start_date','<=',$data['start_date'])->where('end_date','>=',$data['start_date'])->exists() || PromotionLoyalty::where(['store_id' => $data['store_id'], 'status' => '1'])->where('start_date','<=',$data['end_date'])->where('end_date','>=',$data['end_date'])->exists() || PromotionLoyalty::where(['store_id' => $data['store_id'], 'status' => '1'])->where('start_date','>=',$data['start_date'])->where('end_date','<=',$data['end_date'])->exists())
        {
            return redirect('kitchen/loyalty/list')->with('error', __('messages.loyaltyExistError'));
        }

		// Create loyalty
		$loyaltyId = PromotionLoyalty::create($data)->id;

        // Add dish_type into 'promotion_loyalty_dish_type'
        if($loyaltyId)
        {
            $dish_type = $request->dish_type_id;

            if( !empty($dish_type) )
            {
                foreach($dish_type as $dish_type_id)
                {
                    PromotionLoyaltyDishType::create(['loyalty_id' => $loyaltyId, 'dish_type_id' => $dish_type_id]);
                }
            }
        }

		return redirect('kitchen/loyalty/list')->with('success', __('messages.loyaltyCreated'));
    }

    /**
     * Show edit loyalty view
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function edit($id)
    {
        // Get store
        $store = Store::select(['store_id', 'store_name'])->where(['u_id' => Auth::user()->u_id])->get();

        // Get dish type
        $dishType = DishType::select(['dish_id', 'dish_lang', 'dish_name'])
            ->where(['u_id' => Auth::user()->u_id, 'dish_activate' => 1])
            ->orderBy('rank')
            ->get();

        return view('kitchen.loyalty.edit', compact('store', 'dishType', 'id'));
    }

    /**
     * Get loyalty by ID
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function ajaxGetLoyaltyById($id)
    {
        $status = 0;

        // Get loyalty
        $loyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
            ->select(['PL.id', 'PL.store_id', 'PL.quantity_to_buy', 'PL.quantity_get', 'PL.validity', 'PL.start_date', 'PL.end_date', DB::raw('GROUP_CONCAT(DISTINCT PLDT.dish_type_id) AS dish_type_ids'), DB::raw('COUNT(DISTINCT OD.loyalty_id) AS isLoyaltyUsed')])
            ->join('promotion_loyalty_dish_type AS PLDT', 'PLDT.loyalty_id', '=', 'PL.id')
            ->join('dish_type AS DT', 'PLDT.dish_type_id', '=', 'DT.dish_id')
            ->leftJoin('order_details AS OD', 'OD.loyalty_id', '=', 'PL.id')
            ->where(['PL.id' => $id, 'DT.dish_activate' => 1])
            ->groupBy('PL.id')
            ->first();

        if($loyalty)
        {
            $status = 1;
            $loyalty->dish_type_ids = explode(',', $loyalty->dish_type_ids);
        }

        return response()->json(['loyalty' => $loyalty, 'status' => $status]);
    }

    /**
     * Update loyalty
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function update(Request $request)
    {
        // echo '<pre>'; print_r($request->all()); exit;
        // Validation
        $this->validate($request, [
            'loyalty_id' => 'required|numeric',
            'quantity_to_buy' => 'required|numeric',
            'quantity_get' => 'required|numeric|lt:quantity_to_buy',
            'validity' => 'required|numeric',
            'start_date_utc' => 'required',
            'end_date_utc' => 'required|after:start_date_utc',
        ], [
            'end_date_utc.after' => __('messages.discountDateAfter'),
        ]);

        $start_date = \DateTime::createFromFormat('Y/m/d H:i', $request->start_date_utc);
        $end_date = \DateTime::createFromFormat('Y/m/d H:i', $request->end_date_utc);
        $store_id = $request->e_store_id;

        // Check if loyalty already exist
        if(PromotionLoyalty::where(['store_id' => $store_id, 'status' => '1'])->where('start_date','<=',$start_date)->where('end_date','>=',$start_date)->where('id', '!=', $request->loyalty_id)->exists() || PromotionLoyalty::where(['store_id' => $store_id, 'status' => '1'])->where('start_date','<=',$end_date)->where('end_date','>=',$end_date)->where('id', '!=', $request->loyalty_id)->exists() || PromotionLoyalty::where(['store_id' => $store_id, 'status' => '1'])->where('start_date','>=',$start_date)->where('end_date','<=',$end_date)->where('id', '!=', $request->loyalty_id)->exists())
        {
            return redirect('kitchen/loyalty/'.$request->loyalty_id.'/edit')->with('error', __('messages.loyaltyExistError'));
        }

        // Get loyalty
        $loyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
            ->select(['PL.id', 'PL.store_id', 'PL.quantity_to_buy', 'PL.quantity_get', 'PL.validity', 'PL.start_date', 'PL.end_date', DB::raw('GROUP_CONCAT(DISTINCT PLDT.dish_type_id) AS dish_type_ids'), DB::raw('COUNT(DISTINCT OD.loyalty_id) AS isLoyaltyUsed')])
            ->join('promotion_loyalty_dish_type AS PLDT', 'PLDT.loyalty_id', '=', 'PL.id')
            ->join('dish_type AS DT', 'PLDT.dish_type_id', '=', 'DT.dish_id')
            ->leftJoin('order_details AS OD', 'OD.loyalty_id', '=', 'PL.id')
            ->where(['PL.id' => $request->loyalty_id, 'DT.dish_activate' => 1])
            ->groupBy('PL.id')
            ->first();

        if($loyalty)
        {
            // Update loyalty
            if($loyalty->isLoyaltyUsed)
            {
                $data = $request->only(['validity']);
                $data['end_date'] = \DateTime::createFromFormat('Y/m/d H:i', $request->end_date_utc);
            }
            else
            {
                $data = $request->only(['store_id', 'quantity_to_buy', 'quantity_get', 'validity']);
                $data['start_date'] = \DateTime::createFromFormat('Y/m/d H:i', $request->start_date_utc);
                $data['end_date'] = \DateTime::createFromFormat('Y/m/d H:i', $request->end_date_utc);
            }

            PromotionLoyalty::where('id', $request->loyalty_id)->update($data);

            // Update loyalty dish type
            $dish_type = $request->dish_type_id;

            if( !empty($dish_type) )
            {
                PromotionLoyaltyDishType::where(['loyalty_id' => $request->loyalty_id])->delete();

                foreach($dish_type as $dish_type_id)
                {
                    PromotionLoyaltyDishType::create(['loyalty_id' => $request->loyalty_id, 'dish_type_id' => $dish_type_id]);
                }
            }
        }

        return redirect('kitchen/loyalty/list')->with('success', __('messages.loyaltyUpdated'));
    }

    /**
     * Delete loyalty
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        // Get loyalty and count if its been applied on existing order
        $promotionLoyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
            ->select(['PL.id', DB::raw('COUNT(OD.loyalty_id) AS isLoyaltyUsed')])
            ->leftJoin('order_details AS OD', 'OD.loyalty_id', '=', 'PL.id')
            ->where(['PL.id' => $id, 'PL.status' => '1'])
            ->groupBy('PL.id')
            ->first();

        if(!$promotionLoyalty || ($promotionLoyalty && $promotionLoyalty->isLoyaltyUsed))
        {
            return redirect('kitchen/loyalty/list')->with('error', __('messages.loyaltyInvalid'));
        }

        PromotionLoyalty::where('id', $id)->update(['status' => '2']);
        return redirect('kitchen/loyalty/list')->with('success', __('messages.loyaltyDeleted'));
    }
}
