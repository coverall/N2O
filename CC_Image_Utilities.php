<?php
// $Id: CC_Image_Utilities.php,v 1.8 2004/11/02 21:46:10 mike Exp $
//=======================================================================
// FILE: CC_Image_Utilities
//=======================================================================

/**
 * CC_Image_Utilities contains functions available for image manipulation
 * 
 * @package N2O
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

//-------------------------------------------------------------------
// FUNCTION: getImageDimensions
//-------------------------------------------------------------------
 
/**
 * Get the width and height of the image.
 *
 * @param string $imagePath The path to the image.
 * @return array An array with width and height keys.
 */
  
function getImageDimensions($imagePath)
{
	if (function_exists('getimagesize'))
	{
		$imageDimensions = getimagesize($imagePath);
		
		if (!$imageDimensions[0] && !$imageDimensions[1])
		{
			$imageDimensions = array('width' => '??', 'height' => '??');
		}
		else
		{
			$imageDimensions = array('width' => $imageDimensions[0] , 'height' => $imageDimensions[1]);
		}
	}
	else
	{
		$imageDimensions = array('width' => '??', 'height' => '??');
	}
	
	return $imageDimensions;
}


//-------------------------------------------------------------------
// FUNCTION: getThumbnail
//-------------------------------------------------------------------
 
/**
 * Get the thumbnail for the image. If a thumbnail doesn't exist, this will create it.
 *
 * @param string $imagePath The path to the image.
 * @param int $width The desired width for the image thumbnail.
 * @param int $height The desired height for the image thumbnail.
 * @param boolean $generateThumbnail Whether to generate a thumbnail, or not...
 * @return string The path to the thumbnail.
 */
  
function getThumbnail($imagePath, $width = 48, $height = 48, $generateThumbnail = true)
{
	$dimensions = getImageDimensions($imagePath);

	$slashIndex = strrpos($imagePath, '/');
	$dotIndex = strrpos($imagePath, '.');
	
	$thumbnailPath = substr($imagePath, 0, $slashIndex) . '/' . substr($imagePath, $slashIndex + 1, $dotIndex - $slashIndex - 1) . '_icon.png';
	
	if ($generateThumbnail)
	{
		if (!file_exists($thumbnailPath))
		{
			trigger_error($thumbnailPath . ' does not exist. Recreating...', E_USER_WARNING);
			
			createThumbnailImage($imagePath, $thumbnailPath, $width, $height);
	
			if (!file_exists($thumbnailPath))
			{
				trigger_error('Recreating failed.', E_USER_WARNING);
				return;
			}
		}
	}
				
	return $thumbnailPath;
}

//-------------------------------------------------------------------
// FUNCTION: getImageType
//-------------------------------------------------------------------
 
/**
 * Returns the type of image based on the extension.
 *
 * @param string $imagePath The path to the image.
 * @return string The type.
 */
  
function getImageType($imagePath)
{
	if (preg_match('/gif$/i', $imagePath))
	{
		return 'image/gif';
	}
	else if (preg_match('/(jpg|jpeg|jpe)$/i', $imagePath))
	{
		return 'image/jpeg';
	}
	else if (preg_match('/png$/i', $imagePath))
	{
		return 'image/png';
	}
}



//-------------------------------------------------------------------
// FUNCTION: createThumbnailImage
//-------------------------------------------------------------------
 
/**
 * Creates a thumbnail image for an image in the same directory.
 *
 * @param string $imagePath The path to the image.
 * @param string $thumbnailPath The path to the thumbnail.
 * @param int $width The desired width for the image thumbnail.
 * @param int $height The desired height for the image thumbnail.
 * @return boolean True if the image was created successfully, false otherwise.
 */
  
function createThumbnailImage($imagePath, $thumbnailPath, $width = 48, $height = 48)
{
	$imageType = getImageType($imagePath);
	
	if (file_exists($imagePath))
	{
		@chmod($imagePath, 0664);
	}
	
	// create an image icon
	switch ($imageType)
	{
		case 'image/gif':
		{
			if (!function_exists('imagecreatefromgif'))
			{
				trigger_error('The function imagecreatefromgif() is not available to this installation of PHP. Please recompile with full GD support.', E_USER_WARNING);
				return false;
			}
			
			$image = imagecreatefromgif($imagePath);
		}
		break;
		
		case 'image/jpeg':
		{
			if (!function_exists('imagecreatefromjpeg'))
			{
				trigger_error('The function imagecreatefromjpeg() is not available to this installation of PHP. Please recompile with full GD support.', E_USER_WARNING);
				return false;
			}
			
			$image = imagecreatefromjpeg($imagePath);
		}
		break;
		
		case 'image/png':
		{
			if (!function_exists('imagecreatefrompng'))
			{
				trigger_error('The function imagecreatefrompng() is not available to this installation of PHP. Please recompile with full GD support.', E_USER_WARNING);
				return false;
			}
			
			$image = imagecreatefrompng($imagePath);
		}
		break;
		
		default:
		{
			trigger_error('Could not complete because ' . $imageType . ' was not valid (' . $imageType . ').', E_USER_WARNING);
		}
		break;
	}
	
	$dimensions = getImageDimensions($imagePath);

	// If we could not read the image...
	if (!$image)
	{
		$image = imagecreatetruecolor($width, $height);
		$background = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
		$border = imagecolorallocate($image, 0x99, 0x99, 0x99);
		
		$red = imagecolorallocate($image, 0xFF, 0x99, 0x99);
		$redBorder = imagecolorallocate($image, 0xFF, 0x33, 0x33);

		$green = imagecolorallocate($image, 0xCC, 0xFF, 0xCC);
		$greenBorder = imagecolorallocate($image, 0x99, 0xFF, 0x99);

		$blue = imagecolorallocate($image, 0xCC, 0xCC, 0xFF);
		$blueBorder = imagecolorallocate($image, 0x99, 0x99, 0xFF);

		$grey = imagecolorallocate($image, 0xDD, 0xDD, 0xDD);
		$black = imagecolorallocate($image, 0x00, 0x00, 0x00);
		
		imagefilledrectangle($image, 0, 0, $width - 1, $height - 1, $background);
		imagerectangle($image, 0, 0, $width - 1, $height - 1, $border);

		$x = 2;
		while ($x < (int)(($width - 2) / 2))
		{
			imagerectangle($image, $x, $x, $width - 1 - $x, $height - 1 - $x, $grey);
			$x += 2;
		}
		
		imagestring($image, 1, (int)($width / 2) - 4, (int)($height / 2) - 12, 'no', $black);
		imagestring($image, 1, (int)($width / 2) - 12, (int)($height / 2) - 4, 'thumb-', $black);
		imagestring($image, 1, (int)($width / 2) - 8, (int)($height / 2) + 4, 'nail', $black);
		
		imagepng($image, $thumbnailPath);
	}
	// if the image is smaller than the icon width
	else if (($dimensions['width'] <= $width) && ($dimensions['height'] <= $height))
	{
		$thumbnail = imagecreatetruecolor($width, $height);
		$background = imagecolorallocate($thumbnail, 0xFF, 0xFF, 0xFF);
		imagefilledrectangle($thumbnail, 0, 0, $width - 1, $height - 1, $background);
		
		$xoffset = ($width - $dimensions['width']) / 2;
		$yoffset = ($height - $dimensions['height']) / 2;
		
		imagecopy($thumbnail, $image, $xoffset, $yoffset, 0, 0, $dimensions['width'] - 1, $dimensions['height'] - 1);

		imagepng($thumbnail, $thumbnailPath);

		imagedestroy($thumbnail);
	}

	// Else, create the thumbnail image as planned...
	else
	{
		$imageDimensions = getImageDimensions($imagePath);
	
		$smallestSide = min($imageDimensions['width'], $imageDimensions['height']);
		
		$xoffset = ($imageDimensions['width'] - $smallestSide)/2;
		$yoffset = ($imageDimensions['height'] - $smallestSide)/2;
		
		$thumbnail = imagecreatetruecolor($width, $height);

		imagecopyresampled($thumbnail, $image, 0, 0, $xoffset, $yoffset, $width, $height, $smallestSide, $smallestSide);
		
		imagepng($thumbnail, $thumbnailPath);

		imagedestroy($thumbnail);
	}

	imagedestroy($image);
	
	if (file_exists($thumbnailPath))
	{
		chmod($thumbnailPath, 0664);
	}
	else
	{
		trigger_error('Could not create thumbnail image.', E_USER_WARNING);
	}	
}


?>