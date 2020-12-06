<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
use Carbon\Carbon;

class Store extends Model
{
    protected $table = 'store';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    protected $fillable = ['store_id', 'u_id', 'store_type', 'latitude', 'longitude', 'store_name', 'street', 'city', 'country', 'country_code', 'phone', 'email', 'store_link', 's_activ', 'version', 'access_type', 'chain', 'block', 'zip'];

//This function use for testing purpose

    public static function getListRestaurantsCheck($latitude,$longitude,$radius,$companytype1,$companytype2,$todayDate,$currentTime,$todayDay){
        if($radius == null){
            $radius = 10;
        }
        
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
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'.$todayDay.'%')->where('s_activ','=','1')->where('store_type','=',$companytype1)->with('products')->union(Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'."All".'%')->where('s_activ','=','1')->where('store_type','=',$companytype1)->with('products'))->union(Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'.$todayDay.'%')->where('s_activ','=','1')->where('store_type','=',$companytype2)->with('products'))->union(Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'.'All'.'%')->where('s_activ','=','1')->where('store_type','=',$companytype2)->with('products'))->get();
            dd($latLngList);

    //previous Querry    
        $latLngList = Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_days', 'like', '%'.$todayDay.'%')->where('store_open', '<', $currentTime)->where('store_close', '>', $currentTime)->where('s_activ','=','1')->where('store_type','=',$companytype1)->with('products');

        $latLngList1 = Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_days', 'like', '%'.$todayDay.'%')->where('store_open', '<', $currentTime)->where('store_close', '>', $currentTime)->where('s_activ','=','1')->where('store_type','=',$companytype2)->with('products');


            $results = $latLngList->union($latLngList1)->get();

        return $results;
    }

    public static function getRestaurantsList($latitude,$longitude,$radius,$companytype1,$companytype2)
    {
        if($radius == null){
            $radius = 10;
        }
  
        $circle_radius = 6378.10;
        $max_distance = $radius;
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

    public static function getListRestaurants($latitude,$longitude,$radius,$companytype1,$companytype2,$todayDate,$currentTime,$todayDay)
    {
        if($radius == null){
            $radius = 10;
        }

        if (Session::get('timezone')!=null) {
            $datetime = $todayDate . " " . $currentTime;
            $tz_from = Session::get('timezone');
            $tz_to = 'UTC';

            $dt = new \DateTime($datetime, new \DateTimeZone($tz_from));
            $dt->setTimeZone(new \DateTimeZone($tz_to));

            $datePieces = explode(" ", $dt->format('D d-m-Y H:i:s'));
            $todayDay = $datePieces['0'];
            $todayDate = $datePieces['1'];
            $currentTime = $datePieces['2']; 
        }

        $circle_radius = 6378.10;
        $max_distance = $radius;
        $unit = 6378.10;
        $lat = $latitude;
        $lng = $longitude;
        $radius = $radius;

        $dateNow = date("Y-m-d",time()); $timeNow = date("H:i:s",time());

        $latLngList = Store::from('store AS S')
            ->select(['S.store_id', 'S.tagline', 'S.islive', DB::raw("TIMESTAMPDIFF(MINUTE, S.islive, UTC_TIMESTAMP()) AS heartbeat"), 'S.latitude', 'S.longitude', 'S.store_name', 'S.large_image AS store_large_image', 'S.store_open_close_day_time', DB::raw("($unit * ACOS(COS(RADIANS(".$lat.")) * COS(RADIANS(latitude)) * COS(RADIANS(".$lng.") - RADIANS(longitude)) + SIN(RADIANS(".$lat.")) * SIN(RADIANS(latitude)))) AS distance")])
            ->join('company', 'company.u_id', '=', 'S.u_id')
            ->join('product_price_list','S.store_id','=','product_price_list.store_id')
            ->leftJoin('product', 'product_price_list.product_id', '=', 'product.product_id')
            ->leftJoin('dish_type', 'dish_type.dish_id', '=', 'product.dish_type')
            ->whereIn('S.store_type', [1, 3])
            ->where('S.store_close_dates', 'not like', '%'.$todayDate.'%')
            ->where(function($query) use($todayDay) {
                return $query->where('S.store_open_close_day_time', 'like', '%'.$todayDay.'%')
                    ->orWhere('S.store_open_close_day_time', 'like', '%'."All".'%');
            })
            ->where('S.s_activ','=','1')
            ->where('dish_type.dish_activate',1)
            ->where('product_price_list.publishing_start_date','<=',$dateNow)
            ->where('product_price_list.publishing_end_date','>=',$dateNow)
            ->groupBy('S.store_id')
            ->having('distance','<=',$radius)
            ->orderBy('distance')
            ->get();


        /*$latLngList = Store::having('distance','<=',$radius)
        ->select("*", 'store.large_image AS store_large_image',DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')
            ->join('product_price_list','store.store_id','=','product_price_list.store_id')
            ->leftJoin('product', 'product_price_list.product_id', '=', 'product.product_id')
            ->leftJoin('dish_type', 'dish_type.dish_id', '=', 'product.dish_type')
            ->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'.$todayDay.'%')
            ->where('store.s_activ','=','1')->where('dish_type.dish_activate',1)
            ->whereIn('store_type', [1, 3])->where('product_price_list.publishing_start_date','<=',Carbon::now())->where('product_price_list.publishing_end_date','>=',Carbon::now())->groupBy('store.store_id')->with('products')
            ->union(Store::having('distance','<=',$radius)->select("*", 'store.large_image AS store_large_image',DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')
            ->join('product_price_list','product_price_list.store_id','=','store.store_id')
            ->leftJoin('product', 'product_price_list.product_id', '=', 'product.product_id')
            ->leftJoin('dish_type', 'dish_type.dish_id', '=', 'product.dish_type')
            ->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'."All".'%')->where('store.s_activ','=','1')->where('dish_type.dish_activate',1)->whereIn('store_type', [1, 3])->where('product_price_list.publishing_start_date','<=',Carbon::now())->where('product_price_list.publishing_end_date','>=',Carbon::now())->groupBy('store.store_id')->with('products'))            
            ->orderBy('distance')
            ->get();*/

/*
        $latLngList = Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->join('product_price_list','product_price_list.store_id','=','store.store_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'.$todayDay.'%')->where('s_activ','=','1')->where('store_type','=',$companytype1)->with('products')->where('product_price_list.publishing_start_date','<=',Carbon::now())->where('product_price_list.publishing_end_date','>=',Carbon::now())->union(Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->join('product_price_list','product_price_list.store_id','=','store.store_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'."All".'%')->where('s_activ','=','1')->where('store_type','=',$companytype1)->with('products'))->union(Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->join('product_price_list','product_price_list.store_id','=','store.store_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'.$todayDay.'%')->where('s_activ','=','1')->where('store_type','=',$companytype2)->with('products'))->union(Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->join('product_price_list','product_price_list.store_id','=','store.store_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'.'All'.'%')->where('s_activ','=','1')->where('store_type','=',$companytype2)->with('products'))->get();
*/

        return $latLngList;
    }

    public static function getEatLaterListRestaurants($latitude,$longitude,$radius,$companytype1,$companytype2,$todayDate,$currentTime,$todayDay)
    {
        if($radius == null){
            $radius = 10;
        }

        if (Session::get('timezone')!=null) {
            $datetime = $todayDate . " " . $currentTime;
            $tz_from = Session::get('timezone');
            $tz_to = 'UTC';

            $dt = new \DateTime($datetime, new \DateTimeZone($tz_from));
            $dt->setTimeZone(new \DateTimeZone($tz_to));

            $datePieces = explode(" ", $dt->format('D d-m-Y H:i:s'));
            $todayDay = $datePieces['0'];
            $todayDate = $datePieces['1'];
            $currentTime = $datePieces['2']; 
        }

        $circle_radius = 6378.10;
        $max_distance = $radius;
        $unit = 6378.10;
        $lat = $latitude;
        $lng = $longitude;
        $radius = $radius;
        $dateNow = date("Y-m-d",time()); $timeNow = date("H:i:s",time());
        $latLngList = Store::from('store AS S')
            ->select(['S.store_id', 'S.tagline', 'S.latitude', 'S.longitude', 'S.store_name', 'S.large_image AS store_large_image', 'S.store_open_close_day_time_catering', DB::raw("($unit * ACOS(COS(RADIANS(".$lat.")) * COS(RADIANS(latitude)) * COS(RADIANS(".$lng.") - RADIANS(longitude)) + SIN(RADIANS(".$lat.")) * SIN(RADIANS(latitude)))) AS distance")])
            ->join('company', 'company.u_id', '=', 'S.u_id')
            ->join('product_price_list','S.store_id','=','product_price_list.store_id')
            ->leftJoin('product', 'product_price_list.product_id', '=', 'product.product_id')
            ->leftJoin('dish_type', 'dish_type.dish_id', '=', 'product.dish_type')
            ->whereIn('S.store_type', [2, 3])
            ->where('S.store_close_dates', 'not like', '%'.$todayDate.'%')
            ->where(function($query) use($todayDay) {
                return $query->where('S.store_open_close_day_time_catering', 'like', '%'.$todayDay.'%')
                    ->orWhere('S.store_open_close_day_time_catering', 'like', '%'."All".'%');
            })
            ->where('S.s_activ','=','1')
            ->where('dish_type.dish_activate',1)
            ->where('product_price_list.publishing_start_date','<=',$dateNow)
            ->where('product_price_list.publishing_end_date','>=',$dateNow)
            ->groupBy('S.store_id')
            ->having('distance','<=',$radius)
            ->orderBy('distance')
            ->get();

        /*$latLngList = Store::having('distance','<=',$radius)
        ->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')
            ->join('product_price_list','store.store_id','=','product_price_list.store_id')
            ->leftJoin('product', 'product_price_list.product_id', '=', 'product.product_id')
            ->leftJoin('dish_type', 'dish_type.dish_id', '=', 'product.dish_type')
            ->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'.$todayDay.'%')
            ->where('store.s_activ','=','1')->where('dish_type.dish_activate',1)->whereIn('store_type', [2, 3])->where('product_price_list.publishing_start_date','<=',Carbon::now())->where('product_price_list.publishing_end_date','>=',Carbon::now())->groupBy('store.store_id')->with('products')
            ->union(Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')
            ->join('product_price_list','product_price_list.store_id','=','store.store_id')
            ->leftJoin('product', 'product_price_list.product_id', '=', 'product.product_id')
            ->leftJoin('dish_type', 'dish_type.dish_id', '=', 'product.dish_type')
            ->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'."All".'%')->where('store.s_activ','=','1')->where('dish_type.dish_activate',1)->whereIn('store_type', [2, 3])->where('product_price_list.publishing_start_date','<=',Carbon::now())->where('product_price_list.publishing_end_date','>=',Carbon::now())->groupBy('store.store_id')->with('products'))            
            ->get();*/

        return $latLngList;
    }

    public function products()
    {
        return $this->hasMany('App\Product','company_id','company_id');
    }

    public function dishTypes()
    {
        return $this->hasMany('App\DishType','dish_id','dish_type')->where('dish_activate',1);
    }

     public function companies()
    {
        return $this->hasMany('App\Company','company_id','company_id');
    }

    public function product_price_list(){
        return $this->hasMany('App\ProductPriceList','store_id','store_id');
    }

    public function publishing_dates2(){
        $dateNow = date("Y-m-d",time()); $timeNow = date("H:i:s",time());
        return $this->hasMany('App\ProductPriceList','product_id','product_id')->where('publishing_start_date','<=',$dateNow)->where('publishing_end_date','>=',$dateNow);
    }

    /**
     * Get the comments for the blog post.
     */
    public function deliveryTypes()
    {
        return $this->hasMany('App\StoreDeliveryType', 'store_id', 'store_id');
    }
}
