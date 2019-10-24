<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreDeliveryPriceModelDistance extends Model
{
	protected $table = 'store_delivery_price_model_distances';

	public $timestamps = false;

	protected $fillable = ['id', 'store_delivery_price_model_id', 'distance', 'delivery_charge'];

	// public $incrementing = false;
	protected $casts = [
	    'id' => 'string',
	];
}
