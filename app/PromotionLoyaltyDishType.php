<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionLoyaltyDishType extends Model
{
	protected $table = 'promotion_loyalty_dish_type';

	public $timestamps = false;

	protected $fillable = ['loyalty_id', 'dish_type_id'];
}
