<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Store extends Model
{
    //
	protected $table = 'store';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    protected $fillable = ['store_id', 'u_id', 'store_type', 'latitude', 'longitude', 'store_name', 'street', 'city', 'country', 'country_code', 'phone', 'email', 'store_link', 's_activ', 'version', 'access_type', 'chain', 'block', 'zip'];

    public static function getRestaurantsList($latitude,$longitude,$radius,$companytype1,$companytype2)
    {
  
        $circle_radius = 6378.10;
        $max_distance = $radius;

        //$unit = ($unit === "km") ? 6378.10 : 3963.17;
        $unit = 6378.10;
        $lat = (float) $latitude;
        $lng = (float) $longitude;
        $radius = (double) $radius;
        
        $latLngList = Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('s_activ','=','1')->where('store_type','=',$companytype1)->orWhere('store_type','=',$companytype2)->with('products')->get();
    	return $latLngList;
    }

    public static function getListRestaurants($latitude,$longitude,$radius,$companytype1,$companytype2)
    {
    	$circle_radius = 6378.10;
		$max_distance = $radius;

		//$unit = ($unit === "km") ? 6378.10 : 3963.17;
		$unit = 6378.10;
	    $lat = $latitude;
	    $lng = $longitude;
	    $radius = $radius;
      	// $latLngList = Store::having('distance','<=',$radius)->select("*",DB::raw("
       //                  ($unit * ACOS(COS(RADIANS(".$lat."))
       //                      * COS(RADIANS(latitude))
       //                      * COS(RADIANS(".$lng.") - RADIANS(longitude))
       //                      + SIN(RADIANS(".$lat."))
       //                      * SIN(RADIANS(latitude)))) AS distance")
       //      )->join('company', 'company.u_id', '=', 'store.u_id')->where('company.company_type','=',$companytype1)->orWhere('company.company_type','=',$companytype2)->with('products')->get();

        $latLngList = Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('s_activ','=','1')->where('store_type','=',$companytype1)->orWhere('store_type','=',$companytype2)->with('products')->get();
    	return $latLngList;
    }

    public function products()
    {
    	return $this->hasMany('App\Product','company_id','company_id');
    }

     public function companies()
    {
    	return $this->hasMany('App\Company','company_id','company_id');
    }
}
