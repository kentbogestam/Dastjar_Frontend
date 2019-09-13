<?php
namespace App\Libraries;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class StarCloudPrintStarLineModeJob
{
	const SLM_NEW_LINE_ASC = "\x0A";
	const SLM_SET_EMPHASIZED_ASC = "\x1B\x45";
	const SLM_CANCEL_EMPHASIZED_ASC = "\x1B\x46";
	const SLM_SET_LEFT_ALIGNMENT_ASC = "\x1B\x1D\x61\x00";
	const SLM_SET_CENTER_ALIGNMENT_ASC = "\x1B\x1D\x61\x01";
	const SLM_SET_RIGHT_ALIGNMENT_ASC = "\x1B\x1D\x61\x02";
	const SLM_FEED_FULL_CUT_ASC = "\x1B\x64\x02";
	const SLM_FEED_PARTIAL_CUT_ASC = "\x1B\x64\x03";
	const SLM_CODEPAGE_ASC = "\x1B\x1D\x74";
	
	private $printerMac;
	private $filePath;
	private $fileName = array();
	private $printJobBuilder = "";

	public function __construct($printerMac, $fileName)
	{
		$this->printerMac = $printerMac;
		$this->filePath = 'printerdata/';
		$this->fileName = $fileName;
		$printJobBuilder = "";
	}

	public function set_text_emphasized()
	{
		$this->printJobBuilder .= self::SLM_SET_EMPHASIZED_ASC;
	}
	
	public function cancel_text_emphasized()
	{
		$this->printJobBuilder .= self::SLM_CANCEL_EMPHASIZED_ASC;
	}
	
	public function set_text_left_align()
	{
		$this->printJobBuilder .= self::SLM_SET_LEFT_ALIGNMENT_ASC;
	}
	
	public function set_text_center_align()
	{
		$this->printJobBuilder .= self::SLM_SET_CENTER_ALIGNMENT_ASC;
	}
	
	public function set_text_right_align()
	{
		$this->printJobBuilder .= self::SLM_SET_RIGHT_ALIGNMENT_ASC;
	}

	public function set_codepage($codepage)
	{
		$this->printJobBuilder .= self::SLM_CODEPAGE_ASC.$codepage;
	}

	public function add_text_line($text)
	{
		$this->printJobBuilder .= $text.self::SLM_NEW_LINE_ASC;
	}
	
	public function add_new_line($quantity)
	{
		for ($i = 0; $i < $quantity; $i++)
		{
			$this->printJobBuilder .= self::SLM_NEW_LINE_ASC;
		}
	}

	// 
	public function saveJob()
	{
		$printJobBuilder = $this->printJobBuilder.self::SLM_FEED_PARTIAL_CUT_ASC;

		if( !empty($this->fileName) )
		{
			foreach($this->fileName as $file)
			{
				Storage::put($this->filePath.$file, $printJobBuilder);
			}
		}
	}
}
?>