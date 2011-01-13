<?php
// $Id: CC_Folder_Component.php,v 1.19 2004/08/04 01:22:15 patrick Exp $
//=======================================================================
// CLASS: CC_Folder_Component
//=======================================================================

/** This is a class that will allow you to list files in a specified directory and perform operations on the files such as delete, rename and copy.
  *
  * @package CC_Components
  * @access public
  * @author The Crew <N2O@coverallcrew.com>
  * @copyright Copyright &copy; 2003, Coverall Crew
  */

class CC_Folder_Component extends CC_Component
{
	/**
     * The directory we will be showing.
     *
     * @var string $_directory
     * @access private
     */

	var $_directory;

	/**
     * Indicates by a boolean value whether we can delete a file in the directory. Default is false.
     *
     * @var bool $_allowDelete
     * @access private
     */

	var $_allowDelete = false;


	/**
     * The delete file button.
     *
     * @var CC_Button $_deleteButton
     * @access private
     */
    
    var $_deleteButton;


	/**
     * The summary's background colour as a hexadecimal RGB string.
     *
     * @var string $backgroundColour
     * @access private
     */	

	var $backgroundColour;


	/**
     * The colour of the summary's header row as a hexadecimal RGB string.
     *
     * @var string $columnHeaderColour
     * @access private
     */	

	var $columnHeaderColour;


	/**
     * The colour of the summary's even rows as a hexadecimal RGB string.
     *
     * @var string $evenRowColour
     * @access private
     */	

	var $evenRowColour;


	/**
     * The colour of the summary's odd rows as a hexadecimal RGB string.
     *
     * @var string $oddRowColour
     * @access private
     */

	var $oddRowColour;


	/**
     * The colour of the summary's button row as a hexadecimal RGB string.
     *
     * @var string $buttonBarColour
     * @access private
     */	

	var $buttonBarColour;


	/**
     * The highlight colour of the summary row.
     *
     * @var string $rowHighlightColour
     * @access private
     */	

	var $rowHighlightColour;


	/**
     * The cell spacing for the summary's HTML table.
     *
     * @var int $cellspacing
     * @access private
     */	

	var $cellspacing = 0;


	/**
     * The cell padding for the summary's HTML table.
     *
     * @var int $cellpadding
     * @access private
     */	

	var $cellpadding = 2;


	/**
     * An multidimentional array of files in the directory.
     *   For each element in the array, it contain this array structure.
     *   _folderContents[x][0] string of the filename
     *   _folderContents[x][1] string of the filesize
     *   _folderContents[x][2] boolean whether is a directory (true if it is a dir)
     *   _folderContents[x][3] unix timestamp of the file creation, false if there is an error.
     *
     * @var array $_folderContents
     * @access private
     */
    
	var $_folderContents;


	/**
     * An array of checkbox fields to use for actions on files in a folder.
     *
     * @var array $_checkboxes
     * @access private
     */
    
	var $_checkboxes;
    

	/**
     * An array of items to filter out of the directory listing.
     *
     * @var array $_filter
     * @access private
     */
    
	var $_filter;
    

	/**
     * A filter to use to format the output.
     *
     * @var CC_Summary_Filter $_fileSizeFilter
     * @access private
     */
    
	var $_fileSizeFilter;


	/**
     * A flag which will reverse the filter. That is, the file types you specify in the filter will be the only files you *do* see.
     *
     * @var boolean $_reverseFilter
     * @access private
     */
    
	var $_reverseFilter = false;
    


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

	function CC_Folder_Component($name, $directory, $allowDelete = false, $deleteButtonLabel = 'Delete Selected')
	{
		global $ccContentBackgroundColour, $ccTitleBarColour, $ccRecordOddRowColour, $ccRecordEvenRowColour, $ccButtonBarRowColour, $ccRecordHighlightRowColour, $ccDefaultRecordsPerPage;
		
		$this->setName($name);
		
		if (substr($directory, strlen($directory) - 1, strlen($directory)) != '/')
		{
			$directory .= '/';
		}
		
		$this->_directory = $directory;
		
		if ($allowDelete)
		{
			$this->_allowDelete = $allowDelete;
			$this->_deleteButton = &new CC_Button($deleteButtonLabel);
			$this->_deleteButton->registerHandler(new CC_Delete_Folder_File_Handler($this));
		}

		$this->backgroundColour   = $ccContentBackgroundColour;	// the background colour
		$this->columnHeaderColour = $ccTitleBarColour;		// the colour of the header row
		$this->evenRowColour      = $ccRecordEvenRowColour;	// the colour of even rows
		$this->oddRowColour       = $ccRecordOddRowColour;	// the colour of odd rows
		$this->buttonBarColour    = $ccButtonBarRowColour;	// the colour of the button row
		$this->rowHighlightColour = $ccRecordHighlightRowColour; // the colour of the highlight shading
		
		$this->_fileSizeFilter = &new CC_File_Size_Filter();
	}


	//-------------------------------------------------------------------
	// METHOD: updateFolder()
	//-------------------------------------------------------------------

	/**
	 * This method gets a folders contents and puts it into an array called _folderContents.
	 *
	 * @access public
	 */

	function updateFolder($constructing = false)
	{
		$application = &getApplication();
		
		$this->_folderContents = array();
		$this->_checkboxes = array();
		
		$window = &$application->getCurrentWindow();
		
		if ($handle = opendir($this->_directory))
		{
			$rowNumber = 0;
			while (false !== ($file = readdir($handle)))
			{
				if (!ereg('^[\.]', $file) && !$this->filterFile($file, $rowNumber))
				{
					$filesize = filesize($this->_directory . '/' . $file);
					
					$filesizeFormatted = ($filesize > 1024 ? number_format($filesize / 1024) . ' KB' : $filesize . ' B');
					
					$this->_folderContents[$rowNumber][0] = $file;
					$this->_folderContents[$rowNumber][1] = $filesizeFormatted;
					$this->_folderContents[$rowNumber][2] = (is_dir($this->_directory . '/' . $file) ? true : false);
					$this->_folderContents[$rowNumber][3] = filectime($this->_directory . '/' . $file);

					$fieldName = md5($file);
					if ($window->isFieldRegistered($fieldName))
					{
						$checkboxField = &$window->getField($fieldName);
					}
					else
					{
						$checkboxField = &new CC_Checkbox_Field($fieldName, $file);
						$checkboxField->setOptionalValue($file);
					}
					
					$this->_checkboxes[] = &$checkboxField;
					
					unset($checkboxField);
					
					$rowNumber++;
				}
				
			}
			
			closedir($handle);
			
			if (!$constructing)
			{
				$window->registerComponent($this);
			}
		}
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
		
			$html .= '<table border="0" cellspacing="' . $this->cellspacing . '" cellpadding="' . $this->cellpadding . '" width="100%" class="' . $this->style . '">' . "\n";
			$html .= '  <tr bgcolor="' . $this->columnHeaderColour . "\">\n";
			if ($this->_allowDelete)
			{
				$html .= '   <td class="ccSummaryHeadings"></td>' . "\n";
			}
			$html .= '   <td class="ccSummaryHeadings"></td>' . "\n";
			$html .= '   <td class="ccSummaryHeadings">File</td>' . "\n";
			$html .= '   <td class="ccSummaryHeadings">Download</td>' . "\n";
			$html .= '   <td class="ccSummaryHeadings">Size</td>' . "\n";
			$html .= '   <td class="ccSummaryHeadings">Creation</td>' . "\n";
			$html .= " </tr>\n";
		
			for ($rowNumber = 0; $rowNumber < $directorySize; $rowNumber++)
			{
				$file        = $this->_folderContents[$rowNumber][0];
				$filesize    = $this->_folderContents[$rowNumber][1];
				$isDirectory = $this->_folderContents[$rowNumber][2];
				$timestamp   = $this->_folderContents[$rowNumber][3];

				$path = substr($this->_directory, strlen($_SERVER['DOCUMENT_ROOT']));
				
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
					
					if ($isDirectory)
					{
						$html .= ' <td align="center"><img src="/N2O/CC_Images/next.gif" width="18" height="18" border="0"></td>';
					}
					else
					{
						$html .= ' <td align="center"><img src="/N2O/CC_Images/cc_summary.view.gif" width="16" height="18" border="0"></td>';
					}
					
					if ($this->_allowDelete)
					{
						$html .= ' <td> ' . $this->_checkboxes[$rowNumber]->getLabel() . '</td>';
					}
					else
					{
						$html .= ' <td>' . $file . '</td>';
					}
					
					
					if (!$isDirectory)
					{
						$html .= ' <td><nobr><a href="' . $path . $file . '">Download</a></nobr></td>';
					}
					else
					{
						$html .= ' <td></td>';
					}
					
					$html .= ' <td><nobr>' . $filesizeFormatted . '</nobr></td>';
					$html .= ' <td>' . date('M d, Y, H:i', $timestamp) . '</td>';
					
				}
				
				$html .= " </tr>\n";
			}
			$html .= "</table>\n";
	
			if ($this->_allowDelete)
			{
				$html .= '<p>' . $this->_deleteButton->getHTML();
			}
		}
		
		return $html;
	}


	//-------------------------------------------------------------------
	// METHOD: getDeleteButton
	//-------------------------------------------------------------------

	/**
	 * This method returns a reference to the delete button.
	 *
	 * @access public
	 */

	function &getDeleteButton()
	{
		return $this->_deleteButton;
	}



	//-------------------------------------------------------------------
	// METHOD: register
	//-------------------------------------------------------------------

	/**
	 * This is a callback method that gets called by the window when the
	 * component is registered. It's up to the component to decide which
	 * registerXXX() method it should call on the window. Should your
	 * custom component consist of multiple components, you may need to
	 * make multiple calls.
	 *
	 * @access public
	 */

	function register(&$window)
	{
		$this->updateFolder(true);
		
		//$window->registerField($this->_searchField);
		$window->registerButton($this->_deleteButton);

		if ($this->_allowDelete)
		{		
			$window->registerButton($this->_deleteButton);
		}
		
		$window->registerCustomComponent($this);
		
		$size = sizeof($this->_checkboxes);
		
		for ($i = 0; $i < $size; $i++)
		{
			if (!$window->isFieldRegistered($this->_checkboxes[$i]->getName()))
			{
				$window->registerField($this->_checkboxes[$i]);
			}
		}
	}


	//-------------------------------------------------------------------
	// METHOD: get
	//-------------------------------------------------------------------

	/**
	 * This is a callback method that gets called by the window when the
	 * component is retrieved in the else block. It's up to the component
	 * to decide if it wishes to do anything special when this happens.
	 *
	 * @access private
	 */

	function get(&$window)
	{
		if ($window->isComponentRegistered($this->getName()))
		{
			$this->updateFolder();
		}
	}
	

	//-------------------------------------------------------------------
	// METHOD: getFolder
	//-------------------------------------------------------------------

	/**
	 * This method returns the folder we are viewing
	 *
	 * @access public
	 */

	function getFolder()
	{
		return $this->_directory;
	}


	//-------------------------------------------------------------------
	// METHOD: getCheckedFiles
	//-------------------------------------------------------------------

	/**
	 * This method returns an array of all the files in the Folder Component that are checked.
	 * Returns false if none of the files are checked.
	 *
	 * @access public
	 */

	function getCheckedFiles()
	{
		$arrayOfCheckedFiles = array();
		$sizeOfCheckboxArray = sizeof($this->_checkboxes);
		
		for ($i = 0; $i < $sizeOfCheckboxArray; $i++)
		{
			if ($this->_checkboxes[$i]->isChecked())
			{
				$arrayOfCheckedFiles[] = $this->_checkboxes[$i]->getOptionalValue();
			}
		}
		
		if (sizeof($arrayOfCheckedFiles) == 0)
		{
			return false;
		}
		else
		{
			return $arrayOfCheckedFiles;
		}
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
		$filterFile = false;
		$filterSize = sizeof($this->_filter);
		
		for ($i = 0; $i < $filterSize; $i++)
		{
			if (stristr($filename, $this->_filter[$i]))
			{
				$filterFile = true;
			}
		}
		
		return ($this->_reverseFilter ? !$filterFile : $filterFile);
	}


	//-------------------------------------------------------------------
	// METHOD: addFileFilter
	//-------------------------------------------------------------------

	/**
	 * Add a string to filter out specific files.
	 *
	 * @access public
	 * @param string $filterString The String to filter out of the results.
	 * @param boolean $updateTheFolder Update the folder after adding the filter
	 */

	function addFileFilter($filterString, $caseInsensitive = false, $updateTheFolder = false)
	{
		$this->_filter[] = $filterString;

		if ($updateTheFolder)
		{
			$this->updateFolder();
		}
	}


	//-------------------------------------------------------------------
	// METHOD: clearFileFilters
	//-------------------------------------------------------------------

	/**
	 * Clear the array of filters.
	 *
	 * @access public
	 * @param boolean $updateTheFolder Update the folder after removing the filters
	 */

	function clearFileFilters($updateTheFolder = false)
	{
		unset($this->_filter);

		if ($updateTheFolder)
		{
			$this->updateFolder();
		}
	}


	//-------------------------------------------------------------------
	// METHOD: addDeleteButtonHandler
	//-------------------------------------------------------------------

	/**
	 * Add a handler to the delete button, in case you want more flexibility.
	 *
	 * @access public
	 * @param CC_Action_Handler $handler The handler to add.
	 */

	function addDeleteButtonHandler(&$handler)
	{
		if ($this->_allowDelete)
		{
			$this->_deleteButton->registerHandler($handler);
		}
	}


	//-------------------------------------------------------------------
	// METHOD: clearDeleteButtonHandlers
	//-------------------------------------------------------------------

	/**
	 * Clear the handlers from the delete button.
	 *
	 * @access public
	 */

	function clearDeleteButtonHandlers()
	{
		if ($this->_allowDelete)
		{
			$this->_deleteButton->clearHandlers();
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setReverseFilter
	//-------------------------------------------------------------------

	/**
	 * By passing in true, you will reverse the filter; that is, the file types you specify in the filter will be the only files you *do* see.
	 *
	 * @access public
	 */

	function setReverseFilter($reverse)
	{
		$this->_reverseFilter = $reverse;
	}

}

?>