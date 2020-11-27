<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOfferSubSloganLangList extends Model
{
    protected $table = 'product_offer_sub_slogan_lang_list';
    public $timestamps = false;

    protected $fillable = [ 'product_id', 'offer_sub_slogan_lang_list' ];

    public function langData()
    {
    	return $this->hasOne('App\LangText', 'id', 'offer_sub_slogan_lang_list');
    }
}
