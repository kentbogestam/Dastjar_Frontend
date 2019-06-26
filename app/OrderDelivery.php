<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
	protected $table = 'order_delivery';
	public $timestamps = false;

	protected $fillable = ['id', 'order_id', 'driver_id', 'pickup_datetime', 'deliver_datetime'];
}
