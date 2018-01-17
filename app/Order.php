<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'order_id', 'customer_order_id', 'user_id', 'order_type', 'user_type', 'deliver_date', 'deliver_time', 'check_deliveryDate', 'order_total', 'order_delivery_time', 'created_at', 'updated_at'
    ];
}
