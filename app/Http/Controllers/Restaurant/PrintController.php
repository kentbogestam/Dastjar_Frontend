<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrintController extends Controller
{
    function print()
    {
        return view('kitchen.print.receipt1');
    }
}
