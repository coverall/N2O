<?php
// $Id: CC_Image_Folder_Component.php,v 1.10 2008/09/19 22:37:34 patrick Exp $
//=======================================================================
// CLASS: CC_Image_Folder_Component
//=======================================================================

/** This is a class that will allow you to list images in a specified
  * directory and perform operations on the files such as delete, rename and
  * copy.
  *
  * @package CC_Components
  * @access public
  * @author The Crew <N2O@coverallcrew.com>
  * @copyright Copyright &copy; 2003, Coverall Crew
  */

class CC_Image_Folder_Component extends CC_Folder_Component
{

	/**
     * An multidimentional array of image specific things for files in the directory
     *   For each element in the array, it contain this array structure.
     *   _imageFolderContents[x][width] width of the image
     *   _imageFolderContents[x][height] height of the image
     *
     * @var array $_imageFolderContents
     * @access private
     */
    
    var $_imageFolderContents;
    
    var $_thumbnailWidth;
    
    var $_thumbnailHeight;
    
    var $_columns;
    
    var $_viewType; // icon, list, column...



	//-------------------------------------------------------------------
	// CONSTRUCTOR
	//-------------------------------------------------------------------

	/**
	 * This is the constructor.
	 * 
	 * @access public
	 * @param string $name The name to set.
	 * @param string $directory The directory to view.
	 * @param boolean $allowDelete Do we allow deletions of files?
	 * @param string $deleteButtonLabel The label of the search field.
	 */

	function CC_Image_Folder_Component($name, $directory, $thumbnailWidth = 128, $thumbnailHeight = 128, $allowDelete = false, $deleteButtonLabel = 'Delete Selected', $viewType = 'icon',  $columns = 3)
	{
		// add filter before calling the parent so we don't accidently create thumbnails of the thumbnails already created.
		$this->addFileFilter('_icon', true, false);
		
		$this->_thumbnailWidth = $thumbnailWidth;
		$this->_thumbnailHeight = $thumbnailHeight;
		$this->_columns = $columns;
		$this->_viewType = $viewType;
		
		$this->CC_Folder_Component($name, $directory, $allowDelete, $deleteButtonLabel);
		
		$this->clearDeleteButtonHandlers();
		$this->addDeleteButtonHandler(new CC_Delete_Image_Folder_File_Handler($this));
		$this->setStyle('ccImageFolder');
	}


	//-------------------------------------------------------------------
	// METHOD: getHTML()
	//-------------------------------------------------------------------

	/**
	 * This method generates HTML for displaying the directory listing and buttons.
	 *
	 * @access public
	 */

	function getHTML($showDirectoryName = true)
	{
		$html = '';
		
		if ($showDirectoryName)
		{
			$html .= 'Directory Listing (' . $this->_directory . ')<p>';
		}
		
		$directorySize = sizeof($this->_folderContents);
		
		if ($directorySize == 0)
		{
			$html .= 'No files found.';
		}
		else
		{
			if ($this->_viewType == 'icon')
			{
			
				$currentColumn = 0;
			
				$html .= '<table border="0" cellspacing="' . $this->cellspacing . '" cellpadding="' . $this->cellpadding . '" class="' . $this->style . '">' . "\n";
				
				for ($imageNumber = 0; $imageNumber < $directorySize; $imageNumber++)
				{
					$file        = $this->_folderContents[$imageNumber][0];
					$filesize    = $this->_folderContents[$imageNumber][1];
					$isDirectory = $this->_folderContents[$imageNumber][2];
					$timestamp   = $this->_folderContents[$imageNumber][3];
					$imageWidth  = $this->_imageFolderContents[$imageNumber]['width'];
					$imageHeight = $this->_imageFolderContents[$imageNumber]['height'];
					$imageIcon   = $this->_imageFolderContents[$imageNumber]['icon'];
					
					if ($currentColumn == 0)
					{
						$html .= '  <tr>' . "\n";
					}
					
					$path = substr($this->_directory, strlen($_SERVER['DOCUMENT_ROOT']));
					
					//$filesize = filesize($this->_directory . '/' . $file);
					
					$filesizeFormatted = $this->_fileSizeFilter->processValue($filesize);
	
					$className = ($imageNumber % 2 == 0 ? 'even' : 'odd');
	
					$html .= ' <td class="' . $className . '">' . "\n";
					
					$html .= '<table cellpadding="3" cellspacing="0" border="0">';
					$html .= ' <tr>';
					$html .= '  <td class="' . $className . 'Heading" colspan="2" align="left">';
	
					if ($this->_allowDelete)
					{
						$html .= $this->_checkboxes[$imageNumber]->getHTML() . "\n";
						$html .= '<span class="white-space:nowrap">' . $this->_checkboxes[$imageNumber]->getLabel() . '</span>';
					}
					else
					{
						$html .= '<span class="white-space:nowrap">' . $file . '</span>';
					}
					$html .= '  </td>';
					$html .= ' </tr>';
					$html .= ' <tr>';
					$html .= '  <td valign="top">';
					$html .= '   <a href="' . $path . $file . '" target="image' . $imageNumber . '" onclick="window.open(\'' . $path . $file . '\', \'imagepopup' . $imageNumber . '\', \'width=' . $imageWidth . ',height=' . $imageHeight . ',scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0\'); return false">';
					$html .= '   <img src="' . $path . $imageIcon . '" width="' . $this->_thumbnailWidth . '" height="' . $this->_thumbnailHeight . '" border="0">' . "\n";
					$html .= '   </a>';
					$html .= '  </td>';
					$html .= '  <td valign="top">';
					$html .= '   <p><a href="' . $path . $file . '" target="image" onclick="window.open(\'' . $path . $file . '\', \'imagepopup\', \'width=' . $imageWidth . ',height=' . $imageHeight . ',scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0\'); return false">View Fullsize</a></p>';
					$html .= '   <div class="white-space:nowrap">' . $imageWidth . ' x ' . $imageHeight . '</div>';
					$html .= '   <div class="white-space:nowrap">' . $filesizeFormatted . '</div>';
					$html .= '   <div class="white-space:nowrap">' . date('M d, Y, H:i', $timestamp) . '</div>';
					$html .= '  </td>';
					$html .= ' </tr>';
					$html .= '</table>';
	
					$html .= '</td>' . "\n";
					
					$currentColumn++;
					
					
					if ($currentColumn == $this->_columns)
					{
						$html .= '  </tr>' . "\n";
						$currentColumn = 0;
					}
				}
				
				$html .= '</table>' . "\n";
			}
			
			else
			{
				$html .= '<table border="0" cellspacing="' . $this->cellspacing . '" cellpadding="' . $this->cellpadding . '" width="100%" class="' . $this->style . '">' . "\n";
				$html .= '  <tr bgcolor="' . $this->columnHeaderColour . "\">\n";
				if ($this->_allowDelete)
				{
					$html .= '   <td class="ccSummaryHeadings"></td>' . "\n";
				}
				$html .= '   <td class="ccSummaryHeadings"></td>' . "\n";
				$html .= '   <td class="ccSummaryHeadings">File</td>' . "\n";
				$html .= '   <td class="ccSummaryHeadings">Width</td>' . "\n";
				$html .= '   <td class="ccSummaryHeadings">Height</td>' . "\n";
				$html .= '   <td class="ccSummaryHeadings">Size</td>' . "\n";
				$html .= '   <td class="ccSummaryHeadings">Creation</td>' . "\n";
				$html .= " </tr>\n";
			
				for ($rowNumber = 0; $rowNumber < $directorySize; $rowNumber++)
				{
					$file        = $this->_folderContents[$rowNumber][0];
					$filesize    = $this->_folderContents[$rowNumber][1];
					$isDirectory = $this->_folderContents[$rowNumber][2];
					$timestamp   = $this->_folderContents[$rowNumber][3];
					$imageWidth  = $this->_imageFolderContents[$rowNumber]['width'];
					$imageHeight = $this->_imageFolderContents[$rowNumber]['height'];
					$imageIcon   = $this->_imageFolderContents[$rowNumber]['icon'];


					$path = substr($this->_directory, strlen($_SERVER['DOCUMENT_ROOT']));
					
					//$filesize = filesize($this->_directory . '/' . $file);
					
					$filesizeFormatted = $this->_fileSizeFilter->processValue($filesize);
					


					$backgroundcolour = ($rowNumber % 2 == 0 ? $this->evenRowColour : $this->oddRowColour);
					$html .= ' <tr bgcolor="' . $backgroundcolour . '" id="r' . $rowNumber . '" valign="top" onMouseOver="obj=document.getElementById(\'r' . $rowNumber . '\'); obj.style.backgroundColor=\'' . $this->rowHighlightColour . '\'; return true" onMouseOut="obj=document.getElementById(\'r' . $rowNumber . '\'); obj.style.backgroundColor=\'\'; return true" style="ccSummaryHeadings">' . "\n";
			
					if ($file != "." && $file != "..")
					{
						$filesize = filesize($this->_directory . '/' . $file);
						
						$filesizeFormatted = $this->_fileSizeFilter->processValue($filesize);
						
						if ($this->_allowDelete)
						{
							$html .= '   <td align="center">' . $this->_checkboxes[$rowNumber]->getHTML() . '</td>' . "\n";
						}
	
						$html .= '   <td align="center"><a href="' . $path . $file . '" target="image' . $rowNumber . '" onclick="window.open(\'' . $path . $file . '\', \'imagepopup' . $rowNumber . '\', \'width=' . $imageWidth . ',height=' . $imageHeight . ',scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0\'); return false">';
						$html .= '   <img src="' . $path . $imageIcon . '" width="' . $this->_thumbnailWidth . '" height="' . $this->_thumbnailHeight . '" border="0">' . "\n";
						$html .= '   </a></td>';
						
						
						if ($this->_allowDelete)
						{
							$html .= ' <td> ' . $this->_checkboxes[$rowNumber]->getLabel() . '</td>';
						}
						else
						{
							$html .= ' <td>' . $file . '</td>';
						}
						
						$html .= ' <td><nobr>' . $imageWidth . '</nobr></td>';
						$html .= ' <td><nobr>' . $imageHeight . '</nobr></td>';
						
						$html .= ' <td><nobr>' . $filesizeFormatted . '</nobr></td>';
						
						$html .= ' <td>' . date('M d, Y, H:i', $timestamp) . '</td>';
						
					}
					
					$html .= " </tr>\n";
				}
				$html .= "</table>\n";
				
			}
	

			if ($this->_allowDelete)
			{
				$html .= '<p>' . $this->_deleteButton->getHTML();
			}


		}


		
		return $html;
	}


	//-------------------------------------------------------------------
	// METHOD: filterFile
	//-------------------------------------------------------------------

	/**
	 * Determine whether to filter out the file.
	 *
	 * @access public
	 * @param string $filename The name of the file.
	 * @param int $rowNumber This will be helpful for sub-classes.
	 */

	function filterFile($filename, $rowNumber)
	{
		if (preg_match('/(gif|jpg|jpeg|jpe|png)$/i', $filename))
		{
			$filterFile = false;
			$filterSize = sizeof($this->_filter);

			for ($i = 0; $i < $filterSize; $i++)
			{
				if (stristr($filename, $this->_filter[$i]))
				{
					$filterFile = true;
				}
			}
			
			if (!$filterFile)
			{
				$this->getImageDingles($filename, $rowNumber);
			}
			else
			{
				return true;
			}
		}
		else
		{
			return true;
		}
	}
	

	//-------------------------------------------------------------------
	// METHOD: getImageDingles
	//-------------------------------------------------------------------

	/**
	 * Determine whether to filter out the file.
	 *
	 * @access public
	 * @param string $fileName The name of the file.
	 * @param int $rowNumber The row number.
	 */

	function getImageDingles($filename, $rowNumber)
	{
		$file = $this->_directory . $filename;
		
		$imageDimensions = getImageDimensions($file);
		$imageIcon = getThumbnail($file, $this->_thumbnailWidth, $this->_thumbnailHeight);
		
		$slashIndex = strrpos($imageIcon, '/');
		$thumbnailFile = substr($imageIcon, $slashIndex + 1);
		
		
		
		$this->_imageFolderContents[$rowNumber]['width'] = $imageDimensions['width'];
		$this->_imageFolderContents[$rowNumber]['height'] = $imageDimensions['height'];
		$this->_imageFolderContents[$rowNumber]['icon'] = $thumbnailFile;
	}


	//-------------------------------------------------------------------
	// METHOD: setNumColumns
	//-------------------------------------------------------------------

	/**
	 * Sets the number of columns to render.
	 *
	 * @access public
	 * @param int $columns The number of columns.
	 */

	function setNumColumns($columns)
	{
		$this->_columns = $columns;
	}

}

?>