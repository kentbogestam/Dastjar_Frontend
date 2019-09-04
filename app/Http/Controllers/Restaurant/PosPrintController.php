<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

class PosPrintController extends Controller
{
    function printPost(Request $request)
    {
    	$data = $request->all();
        Log::info('printPost');
        Log::info($data);
    }

    function printGet(Request $request)
    {
    	$data = $request->all();
    	Log::info('printGet');
        Log::info($data);
    }

    function printDelete(Request $request)
    {
        $data = $request->all();
    	Log::info('printDelete');
        Log::info($data);
    }
}
