<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionDiscount extends Model
{
	protected $table = 'promotion_discount';

	/**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['store_id', 'code', 'discount_value', 'description', 'start_date', 'end_date', 'status'];

	/**
	 * One discount can only be connected to one store
	 * @return [type] [description]
	 */
	/*function store()
	{
		return $this->hasOne('App\Store', 'store_id', 'store_id');
	}*/
}
