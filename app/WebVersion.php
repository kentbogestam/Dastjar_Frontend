<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebVersion extends Model
{
    protected $table = 'web_version';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    protected $fillable = ['version'];
}
