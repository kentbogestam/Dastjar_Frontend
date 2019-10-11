<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Validation\Rule;

use Auth;
use Session;
use App\Helper;
use App\DishType;
use App\Company;

class DishTypeController extends Controller
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
        $helper = new Helper();
        $dishType = $helper->getDishTypeTree(Auth::user()->u_id);

    	return view('kitchen.dishType.index', compact('dishType'));
    }

    /**
     * Create 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function store(Request $request)
    {
        // Validation
        $this->validate($request, [
            'dish_lang' => 'required',
            'dish_name' => [
                'required',
                Rule::unique('dish_type')->where(function($query) use ($request) {
                    return $query->where(['dish_lang' => $request->dish_lang, 'dish_name' => $request->dish_name, 'u_id' => Auth::user()->u_id, 'dish_activate' => 1, 'parent_id' => $request->parent_id]);
                })
            ],
        ], [
            'dish_name.required' => __('messages.fieldRequired'),
            'dish_name.unique' => __('messages.dishTypeUnique'),
        ]);

        $data = $request->except(['_token']);

        // 
        $data['u_id'] = Auth::user()->u_id;
        $data['company_id'] = Company::where('u_id', Auth::user()->u_id)->first()->company_id;
        
        // Set rank
        $rank = DishType::orderBy('rank', 'DESC')->where(['u_id' => Auth::user()->u_id, 'company_id' => $data['company_id'], 'dish_activate' => 1, 'parent_id' => null])->first();
        
        if($rank)
        {
            $data['rank'] = ($rank->rank + 1);
        }
        else
        {
            $data['rank'] = 1;
        }

        // Create DishType
        DishType::create($data);

        return redirect('kitchen/dishtype/list')->with('success', __('messages.dishTypeCreated'));
    }

    /**
     * Get dish type by ID
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function ajaxGetDishTypeById($id)
    {
        // Get category
        $dishType = DishType::select(['dish_id', 'dish_lang', 'dish_name', 'parent_id'])
            ->where(['dish_id' => $id, 'dish_activate' => 1])->first();

        return response()->json(['dishType' => $dishType]);
    }

    /**
     * [update description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function update(Request $request)
    {
        // Validation
        $this->validate($request, [
            'dish_lang' => 'required',
            'dish_name' => [
                'required',
                Rule::unique('dish_type')->where(function($query) use ($request) {
                    return $query->where(['dish_lang' => $request->dish_lang, 'dish_name' => $request->dish_name, 'u_id' => Auth::user()->u_id, 'dish_activate' => 1, 'parent_id' => $request->parent_id])->where('dish_id', '!=', $request->dish_id);
                })
            ],
        ], [
            'dish_name.required' => __('messages.fieldRequired'),
            'dish_name.unique' => __('messages.dishTypeUnique'),
        ]);

        $data = $request->except(['_token']);

        // Update category
        if($request->dish_id != $request->parent_id)
        {
            DishType::where('dish_id', $request->dish_id)
                ->update(['dish_lang' => $request->dish_lang, 'dish_name' => $request->dish_name, 'parent_id' => $request->parent_id]);
        }
        
        return redirect('kitchen/dishtype/list')->with('success', __('messages.dishTypeUpdated'));
    }

    /**
     * Remove category
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function destroy($id)
    {
        $dishType = DishType::where('dish_id', $id)->first();

        if(!$dishType)
        {
            return redirect('kitchen/dishtype/list')->with('error', __('messages.dishTypeNotFound'));
        }

        // 
        if( is_null($dishType->parent_id) )
        {
            $dishType2 = DishType::where('parent_id', $id)->get();

            if($dishType2)
            {
                foreach($dishType2 as $level2)
                {
                    $dishType3 = DishType::where('parent_id', $level2->dish_id)->get();

                    if($dishType3)
                    {
                        foreach($dishType3 as $level3)
                        {
                            // Remove category 'level3'
                            DishType::where('dish_id', $level3->dish_id)
                                ->update(['dish_activate' => 0]);
                        }
                    }

                    // Remove category 'level2'
                    DishType::where('dish_id', $level2->dish_id)
                        ->update(['dish_activate' => 0]);
                }
            }

            // Remove category 'level1'
            DishType::where('dish_id', $dishType->dish_id)
                ->update(['dish_activate' => 0]);
        }
        else
        {
            DishType::where('dish_id', $id)
                ->orWhere('parent_id', $id)
                ->update(['dish_activate' => 0]);
        }

        return redirect('kitchen/dishtype/list')->with('success', __('messages.dishTypeDeleted'));
    }
}
