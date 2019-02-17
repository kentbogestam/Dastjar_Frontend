<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishType extends Model
{
    protected $table = 'dish_type';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    protected $fillable = ['dish_id', 'dish_lang', 'dish_name', 'company_id', 'u_id', 'dish_activate'];
}
