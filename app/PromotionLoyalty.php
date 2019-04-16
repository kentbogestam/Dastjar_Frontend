<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionLoyalty extends Model
{
	protected $table = 'promotion_loyalty';

	protected $fillable = ['store_id', 'quantity_to_buy', 'quantity_get', 'validity', 'start_date', 'end_date', 'status'];
}
