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
    	$dishType = array();

        // Level1
        $dishTypeLevel1 = $this->getdDishTypeBy(Auth::user()->u_id, null, 'rank');

        if($dishTypeLevel1)
        {
            foreach($dishTypeLevel1 as $level1)
            {
                $dishType[] = array(
                    'dish_id' => $level1->dish_id,
                    'dish_lang' => $level1->dish_lang,
                    'dish_name' => $level1->dish_name,
                    'level' => 0
                );

                // Level2
                $dishTypeLevel2 = $this->getdDishTypeBy(null, $level1->dish_id);
                
                if($dishTypeLevel2)
                {
                    foreach($dishTypeLevel2 as $level2)
                    {
                        $dishType[] = array(
                            'dish_id' => $level2->dish_id,
                            'dish_lang' => $level2->dish_lang,
                            'dish_name' => $level2->dish_name,
                            'level' => 1
                        );

                        // Level3
                        $dishTypeLevel3 = $this->getdDishTypeBy(null, $level2->dish_id);
                        
                        if($dishTypeLevel3)
                        {
                            foreach($dishTypeLevel3 as $level3)
                            {
                                $dishType[] = array(
                                    'dish_id' => $level3->dish_id,
                                    'dish_lang' => $level3->dish_lang,
                                    'dish_name' => $level3->dish_name,
                                    'level' => 2
                                );
                            }
                        }
                    }
                }
            }
        }

    	return view('kitchen.dishType.index', compact('dishType'));
    }

    // 
    function getdDishTypeBy($uId = null, $parentId = null, $orderBy = null)
    {
        $dishType = DishType::select(['dish_id', 'dish_lang', 'dish_name'])
            ->where(['dish_activate' => 1]);

        if( !is_null($uId) )
        {
            $dishType->where('u_id', $uId);
        }

        if( is_null($parentId) )
        {
            $dishType->where('parent_id', null);
        }
        else
        {
            $dishType->where('parent_id', $parentId);
        }

        if( !is_null($orderBy) )
        {
            $dishType->orderBy($orderBy);
        }

        return $dishType->get();
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
        DishType::where('dish_id', $request->dish_id)
            ->update(['dish_lang' => $request->dish_lang, 'dish_name' => $request->dish_name, 'parent_id' => $request->parent_id]);
        
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

    /**
     * Get subcategory of selected category
     * @param  [type] $parentId [description]
     * @return [type]           [description]
     */
    function getSubcategories($dishId)
    {
        // Get category
        $subCategory = DishType::select(['dish_id', 'dish_name'])
            ->where(['parent_id' => $dishId, 'dish_activate' => 1])->get();

        return response()->json(['subCategory' => $subCategory]);
    }
}
