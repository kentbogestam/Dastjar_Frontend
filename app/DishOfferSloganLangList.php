<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishOfferSloganLangList extends Model
{
    protected $table = 'dish_offer_slogan_lang_list';
    public $timestamps = false;

    protected $fillable = [ 'dish_id', 'offer_slogan_lang_list' ];

    public function langData()
    {
    	return $this->hasOne('App\LangText', 'id', 'offer_slogan_lang_list');
    }
}
