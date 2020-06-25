<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsExtra extends Model
{
	protected $fillable = [
        'store_id', 'dish_type_id', 'extra_dish_type_id'
    ];

    public function dishTypeName()
    {
    	return $this->hasOne('App\DishType', 'dish_id', 'dish_type_id');
    }

    public function extraDishTypeName()
    {
    	return $this->hasOne('App\DishType', 'dish_id', 'extra_dish_type_id');
    }
}
