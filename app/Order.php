<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'order_id', 'user_id', 'order_type', 'deliver_date', 'deliver_time', 'order_total', 'order_delivery_time', 'created_at', 'updated_at'
    ];
}
