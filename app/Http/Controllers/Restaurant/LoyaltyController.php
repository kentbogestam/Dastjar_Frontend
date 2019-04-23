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
            ->select(['PL.id', 'PL.quantity_to_buy', 'PL.quantity_get', 'PL.validity', 'PL.start_date', 'PL.end_date', 'S.store_name', DB::raw('GROUP_CONCAT(DT.dish_name) AS dish_name')])
            ->join('store AS S', 'PL.store_id', '=', 'S.store_id')
            ->join('promotion_loyalty_dish_type AS PLDT', 'PLDT.loyalty_id', '=', 'PL.id')
            ->join('dish_type AS DT', 'PLDT.dish_type_id', '=', 'DT.dish_id')
            ->where(['PL.status' => '1', 'S.u_id' => Auth::user()->u_id, 'S.s_activ' => '1'])
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
        $promotionLoyalty = PromotionLoyalty::select('id')
            ->whereRaw("(start_date BETWEEN '{$data['start_date']->format('Y-m-d H:i')}' AND '{$data['end_date']->format('Y-m-d H:i')}' OR end_date BETWEEN '{$data['start_date']->format('Y-m-d H:i')}' AND '{$data['end_date']->format('Y-m-d H:i')}') AND store_id = '{$data['store_id']}' AND status = '1'")
            ->first();

        if($promotionLoyalty)
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
}
