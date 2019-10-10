<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreVirtualMapping extends Model
{
    protected $table = 'store_virtual_mappings';
    
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = ['store_id', 'virtual_store_id'];
}