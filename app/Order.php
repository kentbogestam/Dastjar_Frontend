<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $primaryKey = 'order_id';

    //
    protected $fillable = [
        'order_id', 'customer_order_id', 'user_id', 'order_type', 'user_type', 'deliver_date', 'deliver_time', 'check_deliveryDate', 'order_total', 'order_delivery_time', 'delivery_at_door', 'created_at', 'updated_at'
    ];
}
