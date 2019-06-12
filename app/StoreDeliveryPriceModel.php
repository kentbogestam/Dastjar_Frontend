<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreDeliveryPriceModel extends Model
{
	protected $table = 'store_delivery_price_model';

	protected $fillable = ['id', 'store_id', 'delivery_rule_id', 'delivery_charge', 'threshold', 'status'];

	// public $incrementing = false;
	protected $casts = [
	    'id' => 'string',
	];

    /**
     * Get deliveryRule
     */
    public function deliveryRule()
    {
        return $this->hasOne('App\DeliveryRule', 'id', 'delivery_rule_id');
    }
}
