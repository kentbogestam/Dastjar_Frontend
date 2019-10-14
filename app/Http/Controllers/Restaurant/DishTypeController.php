<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Validation\Rule;

use Auth;
use Session;
use Storage;
use App\Helper;
use App\DishType;
use App\Company;

use \Gumlet\ImageResize;

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
            'dish_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'dish_name.required' => __('messages.fieldRequired'),
            'dish_name.unique' => __('messages.dishTypeUnique'),
        ]);

        $data = $request->except(['_token', 'dish_image']);

        // 
        $data['u_id'] = Auth::user()->u_id;
        $data['company_id'] = Company::where('u_id', Auth::user()->u_id)->first()->company_id;

        // 
        if ($request->hasFile('dish_image'))
        {
            $file = $request->file('dish_image');
            $newFile = new ImageResize($file);
            $newFile->resizeToWidth(500);
            $imageName = 'cat-img-'.time().'.'.$file->getClientOriginalExtension();
            $filePath = 'upload/category/'.$imageName;
            $t = Storage::disk('s3')->put($filePath, $newFile, 'public');
            
            if($t)
            {
                $data['dish_image'] = $filePath;
            }
        }
        
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
        $dishType = DishType::select(['dish_id', 'dish_lang', 'dish_name', 'parent_id', 'dish_image'])
            ->where(['dish_id' => $id, 'dish_activate' => 1])->first();

        return response()->json(['dishType' => $dishType]);
    }

    /**
     * Remove category image
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function removeCategoryImage($id)
    {
        $status = false;

        $dishType = DishType::where('dish_id', $id)->first();

        if($dishType)
        {
            DishType::where('dish_id', $id)->update(['dish_image' => null]);
            Storage::disk('s3')->delete($dishType->dish_image);
            $status = true;
        }

        return response()->json(['status' => $status]);
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
            'dish_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'dish_name.required' => __('messages.fieldRequired'),
            'dish_name.unique' => __('messages.dishTypeUnique'),
        ]);

        $data = $request->except(['_token', 'dish_image']);

        // Update category
        if($request->dish_id != $request->parent_id)
        {
            $filePath = null;

            // 
            if ($request->hasFile('dish_image'))
            {
                // Check if image exist, and then delete
                $dishType = DishType::where('dish_id', $request->dish_id)->first();
                
                if( !is_null($dishType->dish_image) )
                {
                    Storage::disk('s3')->delete($dishType->dish_image);
                }

                // Upload image
                $file = $request->file('dish_image');
                $newFile = new ImageResize($file);
                $newFile->resizeToWidth(500);
                $imageName = 'cat-img-'.time().'.'.$file->getClientOriginalExtension();
                $filePath = 'upload/category/'.$imageName;
                // Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');
                Storage::disk('s3')->put($filePath, $newFile, 'public');
            }

            // 
            DishType::where('dish_id', $request->dish_id)
                ->update(['dish_lang' => $request->dish_lang, 'dish_name' => $request->dish_name, 'parent_id' => $request->parent_id, 'dish_image' => $filePath]);
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
            $dishType2 = DishType::where('parent_id', $dishType->dish_id)->get();

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

                            if( !is_null($level3->dish_image) )
                            {
                                Storage::disk('s3')->delete($level3->dish_image);
                            }
                        }
                    }

                    // Remove category 'level2'
                    DishType::where('dish_id', $level2->dish_id)
                        ->update(['dish_activate' => 0]);

                    if( !is_null($level2->dish_image) )
                    {
                        Storage::disk('s3')->delete($level2->dish_image);
                    }
                }
            }

            // Remove category 'level1'
            DishType::where('dish_id', $dishType->dish_id)
                ->update(['dish_activate' => 0]);

            if( !is_null($dishType->dish_image) )
            {
                Storage::disk('s3')->delete($dishType->dish_image);
            }
        }
        else
        {
            // Delete level2/level3 category, and image from S3 if exist
            $dishTypes = DishType::where('dish_id', $dishType->dish_id)
                ->orWhere('parent_id', $dishType->dish_id)
                ->get();

            if($dishTypes)
            {
                DishType::where('dish_id', $dishType->dish_id)
                    ->orWhere('parent_id', $dishType->dish_id)
                    ->update(['dish_activate' => 0]);

                foreach($dishTypes as $row)
                {
                    if( !is_null($row->dish_image) )
                    {
                        Storage::disk('s3')->delete($row->dish_image);
                    }
                }
            }
        }

        return redirect('kitchen/dishtype/list')->with('success', __('messages.dishTypeDeleted'));
    }
}
