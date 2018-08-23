<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LangText extends Model
{
    protected $table = 'lang_text';
    public $timestamps = false;
    protected $guarded = ['id'];
}
