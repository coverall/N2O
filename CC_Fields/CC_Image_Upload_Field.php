<?php
// $Id: CC_Image_Upload_Field.php,v 1.15 2004/08/04 18:23:47 patrick Exp $
//=======================================================================
// CLASS: CC_Image_Upload_Field
//=======================================================================

/**
 * The CC_Image_Upload_Field represents an upload *image* file (ie. GIF, JPEG or PNG) in the database which is stored in the file system in a specified uploads folder.
 *
 * @package CC_Fields
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Image_Upload_Field extends CC_File_Upload_Field
{		
	/**
     * The width of the display image. 
     *
     * @var int $imageWidth
     * @access private
     */
     
	var $imageWidth;
	
	
	/**
     * The height of the display image. 
     *
     * @var int $imageHeight
     * @access private
     */
     
	var $imageHeight;
	
	
	/**
     * The MIME type of the image. 
     *
     * @var string $imageType
     * @access private
     */
     
	var $imageType;
	
	
	/**
     * The default width of image icons. 
     *
     * @var int $defaultIconWidth
     * @access private
     */
	
	var $defaultIconWidth = 48;
	
	
	/**
     * The default height of image icons. 
     *
     * @var int $defaultIconHeight
     * @access private
     */
	
	var $defaultIconHeight = 48;
	
	
	/**
     * The actual width of image icons. 
     *
     * @var int $iconWidth
     * @access private
     */
	
	var $iconWidth;
	
	
	/**
     * The actual height of image icons. 
     *
     * @var int $iconHeight
     * @access private
     */
	
	var $iconHeight;
	
	
	/**
     * The size of the icon in bytes. 
     *
     * @var int $iconSize
     * @access private
     */
     
	var $iconSize;
	
	
	/**
     * The default height of display images. 
     *
     * @var int $defaultDisplayHeight
     * @access private
     */
     
	var $defaultDisplayWidth = 360;
	
	
	/**
     * The default height of display images. 
     *
     * @var int $defaultDisplayHeight
     * @access private
     */
     
	var $defaultDisplayHeight = 240;
	
	
	/**
     * The actual width of display images. 
     *
     * @var int $displayWidth
     * @access private
     */
     
	var $displayWidth;
	
	
	/**
     * The actual height of display images. 
     *
     * @var int $displayHeight
     * @access private
     */
     
	var $displayHeight;
	
	
	/**
     * The size of the display image in bytes. 
     *
     * @var int $displaySize
     * @access private
     */
     
	var $displaySize;
	
	
	/**
     * The pixel width of the display image's border. 
     *
     * @var int $displayBorder
     * @access private
     */
     
	var $displayBorder = 0;
	
	
	/**
     * The RGB colour of the picture's border.
     *
     * @var string $displayBorderColour
     * @access private
     */
     
	var $displayBorderColour = "ffffff";
		
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Image_Upload_Field
	//-------------------------------------------------------------------

	/** 
	 * The CC_Image_Upload_Field constructor sets its values here, yo. 
	 *
	 * @access public
	 * @param CC_Multiple_File_Upload_Field $parentField The CC_Multiple_File_Upload_Field the field belongs to.
	 * @param CC_Window $window The window the field belongs to.
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows 	which fields to update when users submit data.
	 * @param string $label A text label to accompany the field to describe which input is either expected or displayed.
	 * @param bool $required Whether or not this field must contain data from the user before proceeding. Not required by default.
	 * @param string $rootSavePath The path on the server (relative to the application path) where to save the file.
	 * @param int $maxFileSize The maximum acceptable file size in bytes. The default is 1,000,000,000.
	 * @param string $filePath The full path on the server to the file. Default is blank.
	 */


	function CC_Image_Upload_Field(&$parentField, $name, $label, $required, $rootSavePath, $maxFileSize = 1000000000, $filePath = '')
	{
		$this->CC_File_Upload_Field($parentField, $name, $label, $required, $rootSavePath, $maxFileSize, $filePath);
				
		$this->addFileType('image/jpeg', 'JPEG');
		$this->addFileType('image/pjpeg', 'JPEG (IE)');
		$this->addFileType('image/gif', 'GIF');
		$this->addFileType('image/png', 'PNG');
		$this->addFileType('image/x-png', 'PNG (IE)');
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setFileInfo
	//-------------------------------------------------------------------
	
	/** 
	 * Sets the all the file's info based on information found in the browser's file array. It also maintains path information to the file and sets the image dimensions and image MIME type.
	 *
	 * @access public
	 * @param int $index The index of the current file in the browser's file array.
	 * @todo replace identify 'exec' call with GD stuff
	 */
	 
	function setFileInfo($index = -1)
	{
		parent::setFileInfo($index);

		$filename = $this->getValue();

		if (preg_match('/gif$/i', $filename))
		{
			$this->imageType = 'image/gif';
		}
		else if (preg_match('/(jpg|jpeg|jpe)$/i', $filename))
		{
			$this->imageType = 'image/jpeg';
		}
		else if (preg_match('/png$/i', $filename))
		{
			$this->imageType = 'image/png';
		}
		
		if (file_exists($filename) > 0)
		{
			$this->setImageDimensions();
			$this->displaySize = filesize($this->getDisplayPath());
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setImageDimensions
	//-------------------------------------------------------------------
	
	/** 
	 * Sets the all the file's info based on information found in the browser's file array. It also maintains path information to the file and sets the image dimensions and image MIME type.
	 *
	 * @access public
	 * @param int $index The index of the current file in the browser's file array.
	 * @todo replace identify 'exec' call with GD stuff
	 */
	 
	function setImageDimensions()
	{
		$filename = $this->getValue();

		if (function_exists('getimagesize'))
		{
			$imageDimensions = getimagesize($filename);
			
			if (!$imageDimensions[0] && !$imageDimensions[1])
			{
				$imageDimensions = array('??', '??');
			}
		}
		else
		{
			$imageDimensions = array('??', '??');
		}
	
		$this->imageWidth = $imageDimensions[0];
		$this->imageHeight = $imageDimensions[1];

		unset($filename);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getIconPath
	//-------------------------------------------------------------------
	
	/** 
	 * Returns the full path to the image's icon image file.
	 *
	 * @access public
	 * @return string The path to the file.
	 */
	 	
	function getIconPath()
	{
		if (strlen($this->getValue()) > 0)
		{
			return $this->getRootSavePath() . $this->getIconFilename();
		}
		else
		{
			return '';
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getRelativeIconPath
	//-------------------------------------------------------------------
	
	/** 
	 * Returns the relative path (to the document root) of the image's icon image file.
	 *
	 * @access public
	 * @param bool $forActualResize In the case of an actual resize (the original image is bigger than the icon), we need to make sure we can output in the same format as the source.
	 * @return string The path to the file.
	 */
	 	
	function getRelativeIconPath()
	{
		if (strlen($this->getValue()) > 0)
		{
			return $this->getRelativeRootSavePath() . $this->getIconFilename();
		}
		else
		{
			return '';
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getIconFilename
	//-------------------------------------------------------------------
	
	/** 
	 * Returns the filename of the image's icon image file.
	 *
	 * @access public
	 * @param bool $forActualResize In the case of an actual resize (the original image is bigger than the icon), we need to make sure we can output in the same format as the source.
	 * @return string The path to the file.
	 */
	 	
	function getIconFilename()
	{
		if (strlen($this->getValue()) > 0)
		{
			$slashIndex = strrpos($this->getValue(), '/');
			$dotIndex = strrpos($this->getValue(), '.');
			
			return substr($this->getValue(), $slashIndex + 1, $dotIndex - $slashIndex - 1) . '_icon.png';
		}
		else
		{
			return '';
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getDisplayPath
	//-------------------------------------------------------------------
	
	/** 
	 * Returns the full path to the image's display image file.
	 *
	 * @access public
	 * @return string The path to the file.
	 */
	 
	function getDisplayPath()
	{
		if (strlen($this->getValue()) > 0)
		{
			return $this->getValue();
		}
		else
		{
			return '';
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getIconHeight
	//-------------------------------------------------------------------

	/** 
	 * Returns the pixel height of the icon image.
	 *
	 * @access public
	 * @return int The height of the icon, in pixels.
	 */

	function getIconHeight()
	{	
		return $this->iconHeight;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getIconWidth
	//-------------------------------------------------------------------

	/** 
	 * Returns the pixel width of the icon image.
	 *
	 * @access public
	 * @return int The width of the icon, in pixels.
	 */

	function getIconWidth()
	{
		return $this->iconWidth;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getDisplayHeight
	//-------------------------------------------------------------------

	/** 
	 * Returns the pixel height of the display image.
	 *
	 * @access public
	 * @return int The height of the display image, in pixels.
	 */
	 
	function getDisplayHeight()
	{	
		if (!$this->displayHeight)
		{
			return $this->imageHeight;
		}
		else
		{
			return $this->displayHeight;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getDisplayWidth
	//-------------------------------------------------------------------

	/** 
	 * Returns the pixel width of the display image.
	 *
	 * @access public
	 * @return int The width of the display image, in pixels.
	 */
	 
	function getDisplayWidth()
	{
		if (!$this->displayWidth)
		{
			return $this->imageWidth;
		}
		else
		{
			return $this->displayWidth;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDisplayBorder
	//-------------------------------------------------------------------

	/** 
	 * Returns the pixel width of the display image's border.
	 *
	 * @access public
	 * @return int The width of the display image's border, in pixels.
	 */

	function setDisplayBorder($displayBorder)
	{
		$this->displayBorder = $displayBorder;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDisplayBorderColour
	//-------------------------------------------------------------------

	/** 
	 * Returns the RGB colour value of the display image's border.
	 *
	 * @access public
	 * @return string The colour of the display image's border, in RRGGBB format.
	 */

	function setDisplayBorderColour($displayBorderColour)
	{
		$this->displayBorderColour = $displayBorderColour;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getIcon
	//-------------------------------------------------------------------

	/** 
	 * Returns the HTML for the image's icon.
	 *
	 * @access public
	 * @return string The HTML for the image's icon.
	 * @todo replace identify 'exec' call with PHP's image calls
	 */

	function getIcon()
	{
		$iconPath = $this->getIconPath();
		
		if (!file_exists($iconPath))
		{
			trigger_error($iconPath . ' does not exist. Recreating...', E_USER_WARNING);
			$this->createThumbnailImage();

			if (!file_exists($iconPath))
			{
				trigger_error('Recreating failed.', E_USER_WARNING);
				return;
			}
		}
		
		if (!$this->iconWidth && !$this->iconHeight)
		{
			if (function_exists('getimagesize'))
			{
				$iconDimensions = getimagesize($iconPath);
			}
			else
			{
				$iconDimensions = array($this->defaultIconWidth, $this->defaultIconHeight);
			}
		
			$this->iconWidth = $iconDimensions[0];
			$this->iconHeight = $iconDimensions[1];

			$this->iconSize = filesize($iconPath); 
		}

		unset($iconPath);

		return '<a href="' . $this->getRelativeSavePath() . '" target="_new"><img src="' . $this->getRelativeIconPath() . '" height="' . $this->iconHeight . '"  width="' . $this->iconWidth . '" border="0"></a>';
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getDisplay
	//-------------------------------------------------------------------

	/** 
	 * Returns the HTML for the image's display image.
	 *
	 * @access public
	 * @return string The HTML for the image's display image.
	 * @todo replace identify 'exec' call with PHP's image calls
	 */
	 
	function getDisplay()
	{
		if (!file_exists($this->getDisplayPath()))
		{
			trigger_error('Cannot get display because ' . $this->getDisplayPath() . ' does not exist!', E_USER_WARNING);
			return;
		}
				
		return '<a href="'. $this->getRelativeSavePath() . '" target="_new">' . $this->getImage() . '</a>';
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getImage
	//-------------------------------------------------------------------

	/** 
	 * Returns the HTML for the image itself.
	 *
	 * @access public
	 * @return string The HTML for the image itself.
	 */

	function getImage()
	{
		return '<img src="' . $this->getRelativeSavePath() . '" height="' . $this->displayHeight . '" width="' . $this->displayWidth . '" border="' . $this->displayBorder . '" bordercolor="#' . $this->displayBorderColour . '">';
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getDetailsHTML
	//-------------------------------------------------------------------

	/** 
	 * Returns HTML for the file details, (including the image's pixel width and height and size, in bytes) along with a linkable button for downloading. 
	 *
	 * @access public
	 * @return string HTML for displaying the file details.
	 */
	 
	function getDetailsHTML()
	{
		return '<span class="ccFileName">' . '<a href="'. $this->getRelativeSavePath() . '" target="_new">' . $this->fileName . '</a>' . '</span><br><span class="ccFileDetails">' . $this->getFormattedSize() . '<br>' . $this->imageWidth . 'x' . $this->imageHeight . ' pixels</span></nobr>';
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: moveTempFileToUploadsFolder
	//-------------------------------------------------------------------
	
	/** 
	 * This method copies an image file from temporary browser storage to the file server in the uploads directory. It also creates the icon and display images for the field here.
	 *
	 * @access public
	 * @todo Replace ImageMagick and JPEGTran stuff with PHP's image manipulation functions.
	 */
	 
	function moveTempFileToUploadsFolder()
	{
		parent::moveTempFileToUploadsFolder();

		$this->createThumbnailImage();
	}


	
	//-------------------------------------------------------------------
	// METHOD: createThumbnailImage
	//-------------------------------------------------------------------
	
	/** 
	 * This method creates the thumbnail image.
	 *
	 * @access private
	 */
	 
	function createThumbnailImage()
	{
		$this->setImageDimensions();

		$imageWidth = $this->imageWidth;
		$imageHeight = $this->imageHeight;
		
		if (file_exists($this->getValue()))
		{
			chmod($this->getValue(), 0664);
		}
		
		// create an image icon
		switch ($this->imageType)
		{
			case 'image/gif':
			{
				if (!function_exists('imagecreatefromgif'))
				{
					trigger_error('The function imagecreatefromgif() is not available to this installation of PHP. Please recompile with full GD support.', E_USER_WARNING);
					return false;
				}
				
				$image = imagecreatefromgif($this->getValue());
			}
			break;
			
			case 'image/jpeg':
			{
				if (!function_exists('imagecreatefromjpeg'))
				{
					trigger_error('The function imagecreatefromjpeg() is not available to this installation of PHP. Please recompile with full GD support.', E_USER_WARNING);
					return false;
				}
				
				$image = imagecreatefromjpeg($this->getValue());
			}
			break;
			
			case 'image/png':
			{
				if (!function_exists('imagecreatefrompng'))
				{
					trigger_error('The function imagecreatefrompng() is not available to this installation of PHP. Please recompile with full GD support.', E_USER_WARNING);
					return false;
				}
				
				$image = imagecreatefrompng($this->getValue());
			}
			break;
			
			default:
			{
				trigger_error('Could not complete because ' . $this->imageType . ' was not valid (' . $this->imageType . ').');			
			}
			break;
		}
		
		$iconPath = $this->getIconPath();
		
		// If we could not read the image...
		if (!$image)
		{
			$image = imagecreatetruecolor($this->defaultIconWidth, $this->defaultIconHeight);
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
			
			imagefilledrectangle($image, 0, 0, $this->defaultIconWidth - 1, $this->defaultIconHeight - 1, $background);
			imagerectangle($image, 0, 0, $this->defaultIconWidth - 1, $this->defaultIconHeight - 1, $border);

			$x = 2;
			while ($x < (int)(($this->defaultIconWidth - 2) / 2))
			{
				imagerectangle($image, $x, $x, $this->defaultIconWidth - 1 - $x, $this->defaultIconHeight - 1 - $x, $grey);
				$x += 2;
			}
			
			imagestring($image, 1, (int)($this->defaultIconWidth / 2) - 4, (int)($this->defaultIconHeight / 2) - 12, 'no', $black);
			imagestring($image, 1, (int)($this->defaultIconWidth / 2) - 12, (int)($this->defaultIconHeight / 2) - 4, 'thumb-', $black);
			imagestring($image, 1, (int)($this->defaultIconWidth / 2) - 8, (int)($this->defaultIconHeight / 2) + 4, 'nail', $black);
			
			$this->iconWidth = $this->defaultIconWidth;
			$this->iconHeight = $this->defaultIconHeight;
			
			imagepng($image, $iconPath);
		}
		// if the image is smaller than the icon width
		else if ($this->isImageSmallerThanIcon())
		{
			$thumbnail = imagecreatetruecolor($this->defaultIconWidth, $this->defaultIconHeight);
			$background = imagecolorallocate($thumbnail, 0xFF, 0xFF, 0xFF);
			imagefilledrectangle($thumbnail, 0, 0, $this->defaultIconWidth - 1, $this->defaultIconHeight - 1, $background);
			
			$xoffset = ($this->defaultIconWidth - $imageWidth) / 2;
			$yoffset = ($this->defaultIconHeight - $imageHeight) / 2;
			
			imagecopy($thumbnail, $image, $xoffset, $yoffset, 0, 0, $imageWidth - 1, $imageHeight - 1);
	
			imagepng($thumbnail, $iconPath);
	
			imagedestroy($thumbnail);

			$this->iconWidth = $this->defaultIconWidth;
			$this->iconHeight = $this->defaultIconHeight;
		}
		// Else, create the thumbnail image as planned...
		else
		{
			$smallestSide = min($imageWidth, $imageHeight);
			
			$xoffset = ($imageWidth - $smallestSide)/2;
			$yoffset = ($imageHeight - $smallestSide)/2;
			
			$thumbnail = imagecreatetruecolor($this->defaultIconWidth, $this->defaultIconHeight);

			imagecopyresampled($thumbnail, $image, 0, 0, $xoffset, $yoffset, $this->defaultIconWidth, $this->defaultIconHeight, $smallestSide, $smallestSide);
			
			imagepng($thumbnail, $iconPath);

			imagedestroy($thumbnail);
		}

		imagedestroy($image);
		
		if (file_exists($iconPath))
		{
			chmod($iconPath, 0664);
		}
		else
		{
			trigger_error('Could not create thumbnail image.', E_USER_WARNING);
		}
	}


	//-------------------------------------------------------------------
	// METHOD: isImageSmallerThanIcon
	//-------------------------------------------------------------------

	/** 
	 * This method returns true if the image is smaller than the desired icon size.
	 *
	 * @access private
	 */

	function isImageSmallerThanIcon()
	{
		return (($this->imageWidth <= $this->defaultIconWidth) && ($this->imageHeight <= $this->defaultIconHeight));
	}


	//-------------------------------------------------------------------
	// METHOD: deleteCleanup
	//-------------------------------------------------------------------
	
	/** 
	 * This method is called when the field is deleted. The uploaded file is deleted from the file system along with the icon and display images.
	 *
	 * @access private
	 */

	function deleteCleanup()
	{
		parent::deleteCleanup();
		
		if (file_exists($this->getIconPath()))
		{
			unlink($this->getIconPath());
		}
		
		if (file_exists($this->getDisplayPath()))
		{
			unlink($this->getDisplayPath());
		}
	}
}

?>