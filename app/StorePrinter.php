<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StorePrinter extends Model
{
	protected $table = 'store_printers';

	protected $fillable = ['id', 'store_id', 'printer_type', 'mac_address', 'print_copy', 'status'];

	// public $incrementing = false;
	protected $casts = [
	    'id' => 'string',
	];
}
