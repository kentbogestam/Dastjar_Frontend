<?php
namespace App;

use App\PhocaGalleryImageMagic;

class Resizer
{

function getThumbnailResize($size = 'all') {	
		
		// Get width and height from default settings
	//	$params 			= JComponentHelper::getParams('com_phocagallery') ;
		$large_image_width 	= 800;
		$large_image_height = 600 ;
		$medium_image_width = 501 ;
		$medium_image_height= 301 ;
		$small_image_width 	= 153 ;
		$small_image_height = 98 ;
		$thumb_image_width 	= 120 ;
		$thumb_image_height = 90 ;
		$temp_image_width 	= 90 ;
		$temp_image_height = 90 ;
		$logo_width 	= 430 ;
		$logo_height = 115 ;
		$staff_width 	= 75 ;
		$staff_height = 75 ;
		///////////////////////
		$iphone4_image_width 	= 300 ;
		$iphone4_image_height = 168 ;
//		$iphone4_image_width 	= 247 ;
//		$iphone4_image_height = 130 ;
		$iphone4_cat_image_width = 55;
		$iphone4_cat_image_height = 60 ;

        $smallImg_width = 41 ;
		$smallImg_height = 41 ;
                
        $new_cat_icon_image_width = 45 ;
        $new_cat_icon_image_height = 60 ;

        $brand_image_width = 45 ;
		$brand_image_height = 44 ;
		
		$cms_large_image_width 	= 800;
		$cms_large_image_height = 600 ;
		$cms_medium_image_width = 214 ;
		$cms_medium_image_height= 124 ;
		
		$banner_top_width = 727 ;
		$banner_top_height= 88 ;
		
		$banner_bottom_width = 160 ;
		$banner_bottom_height= 700 ;

		$banner_middle_width = 550 ;
		$banner_middle_height= 88 ;

		switch ($size) {
			
			case 'banner_top':
			$fileResize['width']	=	$banner_top_width;
			$fileResize['height']	=	$banner_top_height;
			break;
			
			case 'banner_middle':
			$fileResize['width']	=	$banner_middle_width;
			$fileResize['height']	=	$banner_middle_height;
			break;
			
			case 'banner_bottom':
			$fileResize['width']	=	$banner_bottom_width;
			$fileResize['height']	=	$banner_bottom_height;
			break;
			
			
			
			case 'cms_large':
			$fileResize['width']	=	$cms_large_image_width;
			$fileResize['height']	=	$cms_large_image_height;
			break;
			
			case 'cms_medium':
			$fileResize['width']	=	$cms_medium_image_width;
			$fileResize['height']	=	$cms_medium_image_height;
			break;
			
			case 'staff':
			$fileResize['width']	=	$staff_width;
			$fileResize['height']	=	$staff_height;
			break;
			
			case 'logo':
			$fileResize['width']	=	$logo_width;
			$fileResize['height']	=	$logo_height;
			break;
			
			case 'large':
			$fileResize['width']	=	$large_image_width;
			$fileResize['height']	=	$large_image_height;
			break;
			
			case 'medium':
			$fileResize['width']	=	$medium_image_width;
			$fileResize['height']	=	$medium_image_height;
			break;
			
			case 'small':
			$fileResize['width']	=	$small_image_width;
			$fileResize['height']	=	$small_image_height;
			break;
			
			case 'thumb':
			$fileResize['width']	=	$thumb_image_width;
			$fileResize['height']	=	$thumb_image_height;
			break;
			
			case 'temp':
			$fileResize['width']	=	$temp_image_width;
			$fileResize['height']	=	$temp_image_height;
			break;
			case 'iphone4':
			$fileResize['width']	=	$iphone4_image_width;
			$fileResize['height']	=	$iphone4_image_height;
			break;

			case 'brand':
			$fileResize['width']	=	$brand_image_width;
			$fileResize['height']	=	$brand_image_height;
			break;
			
			case 'iphone4_cat':
			$fileResize['width']	=	$iphone4_cat_image_width;
			$fileResize['height']	=	$iphone4_cat_image_height;
			break;

                     case 'smallImg':
			$fileResize['width']	=	$smallImg_width;
			$fileResize['height']	=	$smallImg_height;
			break;

                      case 'new_cat_icon':
			$fileResize['width']	=	$new_cat_icon_image_width;
			$fileResize['height']	=	$new_cat_icon_image_height;
			break;
			
			default:
			case 'all':
			$fileResize['smallwidth']	=	$small_width;
			$fileResize['smallheight']	=	$small_height;
			$fileResize['mediumwidth']	=	$medium_width;
			$fileResize['mediumheight']	=	$medium_height;
			$fileResize['largewidth']	=	$large_width;
			$fileResize['largeheight']	=	$large_height;
			break;			
		}

		return $fileResize;
	}

function createFileThumbnail($fileOriginal, $fileThumbnail, $size, $frontUpload=0,$crop_thumbnail, &$errorMsg) {	
		$enable_thumb_creation 		= 1;
		$watermarkParams['create']	= 0; //Watermark
		$watermarkParams['x'] 		= 'right';
		$watermarkParams['y']		= 'bottom';

		$crop = null;

		switch ($size) {
			
			case 'banner_middle':
				if ($crop_thumbnail == 2 || $crop_thumbnail == 4 || $crop_thumbnail == 5 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			case 'banner_top':
				if ($crop_thumbnail == 2 || $crop_thumbnail == 4 || $crop_thumbnail == 5 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			case 'banner_bottom':
				if ($crop_thumbnail == 2 || $crop_thumbnail == 4 || $crop_thumbnail == 5 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			
			case 'cms_large':
				if ($crop_thumbnail == 2 || $crop_thumbnail == 4 || $crop_thumbnail == 5 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			
			case 'cms_medium':
				if ($crop_thumbnail == 2 || $crop_thumbnail == 4 || $crop_thumbnail == 5 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			
			case 'staff':				 
				if ($crop_thumbnail == 3 || $crop_thumbnail == 5 || $crop_thumbnail == 6 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			
			case 'logo':				 
					$crop = 0;				 	
				
			case 'temp':
				if ($crop_thumbnail == 3 || $crop_thumbnail == 5 || $crop_thumbnail == 6 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			case 'thumb':
				if ($crop_thumbnail == 3 || $crop_thumbnail == 5 || $crop_thumbnail == 6 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			case 'small':
				if ($crop_thumbnail == 3 || $crop_thumbnail == 5 || $crop_thumbnail == 6 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			case 'medium':
				if ($crop_thumbnail == 2 || $crop_thumbnail == 4 || $crop_thumbnail == 5 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			case 'large':

			default:
			 $watermarkParams['create']	= 2;// Watermark
				if ($crop_thumbnail == 1 || $crop_thumbnail == 4 || $crop_thumbnail == 6 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
		}		
		
		// disable or enable the thumbnail creation
		if ($enable_thumb_creation == 1) {	

			$fileResize	= $this->getThumbnailResize($size);

			if (file_exists($fileOriginal)) {
				//file doesn't exist, create thumbnail
				if (!file_exists($fileThumbnail)) {
					$errorMsg = 'Error4';
					//Don't do thumbnail if the file is smaller (width, height) than the possible thumbnail
					list($width, $height) = GetImageSize($fileOriginal);

					//larger
				//	phocagalleryimport('phocagallery.image.imagemagic');
					if ($width > $fileResize['width'] || $height > $fileResize['height']) {
						$imageMagic = PhocaGalleryImageMagic::imageMagic($fileOriginal, $fileThumbnail, $fileResize['width'] , $fileResize['height'], $crop, null, $watermarkParams, $frontUpload, $errorMsg);						
					} else {
						$imageMagic = PhocaGalleryImageMagic::imageMagic($fileOriginal, $fileThumbnail, $width , $height, $crop, null, $watermarkParams, $frontUpload, $errorMsg);
					}

					if ($imageMagic) {
						return true;
					} else {
						return false;// error Msg will be taken from imageMagic
					}
				} else {
					$errorMsg = 'ThumbnailExists';//thumbnail exists
					return false;
				}	
			} else {
				$errorMsg = 'ErrorFileOriginalNotExists';
				return false;
			}
			$errorMsg = 'Error3';
			return false;
		} else {
			$errorMsg = 'DisabledThumbCreation'; // User have disabled the thumbanil creation e.g. because of error
			return false;
		}
	}
}
?>
