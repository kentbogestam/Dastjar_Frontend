<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $primaryKey = 'order_id';

    //
    protected $fillable = [
        'order_id', 'customer_order_id', 'user_id', 'order_type', 'user_type', 'deliver_date', 'deliver_time', 'check_deliveryDate', 'order_total', 'order_delivery_time', 'created_at', 'updated_at'
    ];
    
    public function orderdetailDetail()
    {
        return $this->hasMany('App\OrderDetail','order_id','order_id');
    }
    
    public function customerDetail()
    {
        return $this->hasMany('App\Customer','id','user_id');
    }
    
    public function customerFullDetail()
    {
        return $this->hasMany('App\CustomerAddress','customer_id','user_id');
    }
}
