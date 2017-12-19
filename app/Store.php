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


    protected $fillable = ['store_id', 'u_id', 'latitude', 'longitude', 'store_name', 'street', 'city', 'country', 'country_code', 'phone', 'email', 'store_link', 's_activ', 'version', 'access_type', 'chain', 'block', 'zip'];

    public static function getRestaurantsList($latitude,$longitude,$radius,$companytype1,$companytype2)
    {
    	$circle_radius = 3959;
		$max_distance = $radius;
		$lat = $latitude;
		$lng = $longitude;


    	// $latLngList = DB::select(
     //           'SELECT * FROM (SELECT u_id, latitude, longitude, store_name, email, (' . $circle_radius . ' * acos(cos(radians(' . $lat . ')) * cos(radians(latitude)) *
     //                cos(radians(longitude) - radians(' . $lng . ')) +
     //                sin(radians(' . $lat . ')) * sin(radians(latitude))))
     //                AS distance
     //                FROM store) AS distances
	    //             WHERE distances < ' . $max_distance . '
	    //             ORDER BY distances;
     //        ');
    	$latLngList = DB::select(
               'SELECT * FROM (SELECT u_id, latitude, longitude, store_name, email, (' . $circle_radius . ' * acos(cos(radians(' . $lat . ')) * cos(radians(latitude)) * cos(radians(longitude) - radians(' . $lng . ')) + sin(radians(27.398635)) * sin(radians(latitude)))) AS distance FROM store) AS distances join company on company.u_id = distances.u_id WHERE distance < ' . $max_distance . ' and company.company_type = ' . $companytype1 . ' or company.company_type = ' . $companytype2 . '
            ');
    	return $latLngList;
    }

    public static function getListRestaurants($latitude,$longitude,$radius,$companytype1,$companytype2)
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
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('company.company_type','=',$companytype1)->orWhere('company.company_type','=',$companytype2)->with('products')->get();
      	
    	// $latLngList = DB::select(
     //           'SELECT *,company.* FROM (SELECT u_id, store_id, latitude, longitude, store_name, email, (' . $circle_radius . ' * acos(cos(radians(' . $lat . ')) * cos(radians(latitude)) * cos(radians(longitude) - radians(' . $lng . ')) + sin(radians(27.398635)) * sin(radians(latitude)))) AS distance FROM store) AS distances join company on company.u_id = distances.u_id WHERE distance < ' . $max_distance . ' and company.company_type = ' . $companytype1 . ' or company.company_type = ' . $companytype2 . '
     //        ');
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
