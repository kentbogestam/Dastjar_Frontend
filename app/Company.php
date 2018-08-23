<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Company extends Model
{
    //
	protected $table = 'company';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    protected $fillable = ['company_id', 'u_id', 'company_name', 'company_type', 'orgnr', 'street', 'zip', 'city', 'country', 'tzcountries', 'timezones', 'currencies', 'pre_loaded_value', 'budget', 'c_activ', 'seller_id', 'seller_date', 'ccode', 'cc_value', 'low_level', 'paid', 'ba', 'app_Key', 'app_Secret', 'access_token', 'stripe_publishable_key', 'stripe_user_id', 'refresh_token'];

    public function products()
    {
    	return $this->hasMany('App\Product','company_id','company_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','employer');
    }

}
