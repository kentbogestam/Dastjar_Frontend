<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'order_id', 'user_id', 'order_type', 'deliver_date', 'deliver_time', 'price', 'time', 'created_at', 'updated_at'
    ];
}
