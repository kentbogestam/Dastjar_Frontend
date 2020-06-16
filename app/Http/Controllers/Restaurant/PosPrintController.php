<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helper;
// use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

use App\Traits\PosReceipt;

class PosPrintController extends Controller
{
	use PosReceipt;

	// Called from printer to check if there any job to print
    function handlePost(Request $request)
    {
    	$data = $request->all();
        
        if (!isset($data['printerMAC'])) exit;
        
        // Check if file exist belongs to specific 'mac address' (printer)
        $printerMAC = $this->getPrinterFolder($data['printerMAC']);
        $filePath = storage_path('app/printerdata');
    	$fileLike = $filePath.'/'.$printerMAC.'-*';
    	$files = File::glob($fileLike);

        if (!empty($files))
		{
			$arr = array(
				"jobReady" => true,
				"mediaTypes" => array('text/plain'),
				"deleteMethod" => "GET"
			);		
		}
		else
		{
			$arr = array("jobReady" => false);
		}

		echo json_encode($arr);
		exit;
    }

    // If printer find job exist to print/delete
    function handleGet(Request $request)
    {
    	$data = $request->all();

    	if (isset($_GET['delete']))
    	{
    		$this->handleDelete($data);
    	}
    	else
    	{
    		if ($data['mac'] == "") return;

    		// 
	    	$printerMAC = $this->getPrinterFolder($data['mac']);
	    	
	    	$filePath = storage_path('app/printerdata');
	    	$fileLike = $filePath.'/'.$printerMAC.'-*';
	    	$files = File::glob($fileLike);

	    	if(!empty($files))
	    	{
	    		$files = Arr::sort($files);
		    	$file = $files[0];

		    	if (File::exists($file)) 
				{
					header('Content-Type: text/plain');
					echo file_get_contents($file);
				}
	    	}
    	}
    	exit;
    }

    // Handle file delete after print
    function handleDelete($data)
    {
    	Log::info('handleDelete');
		Log::info($data);

        header('Content-Type: text/plain');
		if (isset($data['code']) && ($data['code'] == '200 OK'))
		{
			$printerMAC = $this->getPrinterFolder($data['mac']);
			
			$filePath = storage_path('app/printerdata');
	    	$fileLike = $filePath.'/'.$printerMAC.'-*';
	    	$files = File::glob($fileLike);

	    	if(!empty($files))
	    	{
	    		$files = Arr::sort($files);
		    	$file = $files[0];

				if (File::exists($file)) {
					File::delete($file);
				}
	    	}
		}
    }

    // Printer call this if file needs to be deleted after print the file
    function handleDeleteMethod(Request $request)
    {
    	$data = $request->all();
    	$this->handleDelete($data);
    	exit;
    }

    private function getPrinterFolder($printerMac)
	{
		return str_replace(":", ".", $printerMac);
	}
	
	private function getPrinterMac($printerFolder)
	{
		return str_replace(".", ":", $printerFolder);
	}

	public function PrintCopyReceipt(Request $request)
	{
		$this->createPOSReceipt($request->orderId);
      	Helper::uploadPrintFile();
	}
}
