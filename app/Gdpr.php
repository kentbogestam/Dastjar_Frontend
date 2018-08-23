<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gdpr extends Model
{
    protected $table = 'user_gdpr';
    protected $guarded = ['id'];
}
