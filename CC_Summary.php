<?php
// $Id: CC_Summary.php,v 1.152 2005/02/28 00:11:56 patrick Exp $
//=======================================================================
// CLASS: CC_Summary
//=======================================================================

/**
 * This class provides a visual means to display and manage tables in the database. It lists rows in a given table allowing users, based on their access, to view, edit and delete selected records. It also allows users to download tab-delimited, spreadsheet friendly representations of the contents of an N2O database for import into other software.
 *
 * @package CC_Summaries
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */
	 
class CC_Summary extends CC_Component
{	
	/**
     * The window which houses the summary
     *
     * @var CC_Window $window
     * @access private
     */	
     
	var $window;
	
	
	/**
     * The application's field manager object.
     *
     * @var CC_FieldManager $fieldManager
     * @access private
     * @todo Do we need this if we have the application object everywhere?
     */	
    
    var $fieldManager;				// the field manager which provides us info
	
	
	/**
     * The query to use to generate the rows and columns in the summary.
     *
     * @var string $query
     * @access private
     */	
     
    var $query;
	

	/**
     * The 'main' table the summary is displaying records for.
     *
     * @var string $mainTable
     * @access private
     */	
	
	var $mainTable;


	/**
     * The name of the column to use as the "ID".
     *
     * @var string $_idColumn
     * @access private
     */	

	var $_idColumn = 'ID';


	/**
     * The button that adds new records.
     *
     * @var CC_Button $addNewButton
     * @access private
     */	

	var $addNewButton;
	

	/**
     * The button that downloads shown records and columns as a tab-delimited file, compressed with the ZIP protocol.
     *
     * @var CC_Button $downloadButton
     * @access private
     */	

	var $downloadButton;


	/**
     * The name of the handler to use to download 'all'.
     *
     * @var string $downloadAllHandler
     * @access private
     */	

	var $downloadAllHandler = 'CC_Download_Zip_Summary_Handler';
	
	
	/**
     * The name of the handler to use to download a tab-delimited file.
     *
     * @var string $downloadTabHandler
     * @access private
     */	

	var $downloadTabHandler = 'CC_Download_Zip_Summary_Handler';
	
	
	/**
     * The name of the handler to use to download an XLS file.
     *
     * @var string $downloadXLSHandler
     * @access private
     */	

	var $downloadXLSHandler = 'CC_Download_XLS_Summary_Handler';
	
	
	/**
     * The button that downloads ALL records and columns as a tab-delimited file, compressed with the ZIP protocol.
     *
     * @var CC_Button $downloadAllButton
     * @access private
     */	

	var $downloadAllButton;


	/**
     * The button that downloads ALL records and columns as a Micro$oft Excel file.
     *
     * @var CC_Button $downloadButton
     * @access private
     */	

	var $downloadXLSButton;

	
	/**
     * A variable that indicates whether we should add anything to the query (eg. an order by clause) or is the query already complete
     *
     * @var bool $downloadQueryComplete
     * @access private
     */	

	var $downloadQueryComplete;
	
	
	/**
     * The query for the download all file.
     *
     * @var string $downloadAllQuery
     * @access private
     */	

	var $downloadAllQuery;


	/**
     * The query for the download summary query.
     *
     * @var string $downloadSummaryQuery
     * @access private
     */	

	var $downloadSummaryQuery;		
	

	/**
     * The file name for download all query.
     *
     * @var string $downloadAllFileName
     * @access private
     */	

	var $downloadAllFileName;


	/**
     * The file name for download summary query.
     *
     * @var string $downloadSummaryFileName
     * @access private
     */	

	var $downloadSummaryFileName;
	

	/**
     * The button that refreshes the summary by syncronizing with the database.
     *
     * @var CC_Button $refreshButton
     * @access private
     */

	var $refreshButton;


	/**
     * An array of the summary's sort buttons. These are the buttons that form the column headers and when clicked on, sort by the column they represent (they toggle between ascending and descending).
     *
     * @var array $sortButtons
     * @access private
     */	

	var $sortButtons = array();


	/**
     * The name of the column to sort by as it appears in the database.
     *
     * @var string $sortByColumn
     * @access private
     */	

	var $sortByColumn;


	/**
     * The direction to sort. 'ASC' and 'DESC' are possible values.
     *
     * @var string $sortByDirection
     * @access private
     */	

	var $sortByDirection;


	/**
     * The total number of records in the summary's result set.
     *
     * @var int $numRecords
     * @access private
     */	
	
	var $numRecords = 0;


	/**
     * Determining whether or not the ID column gets displayed in the summary.
     *
     * @var bool $showIdColumn
     * @access private
     */	
	
	var $showIdColumn;


	/**
     * The array of the currently displayed rows.
     *
     * @var array $rows
     * @access private
     */	
	
	var $rows;


	/**
     * An array of strings representing the summary's column names.
     *
     * @var array $columnNames
     * @access private
     */	

	var $columnNames = array();


	/**
     * An array of CC_Summary_Filters representing the summary's filter objects.
     *
     * @var array $columnFilters
     * @access private
     */	

	var $columnFilters = array();


	/**
     * An array of additional/custom columns. Each element should be of the CC_SummaryColumnProvider class.
     *
     * @var array $additionalColumns
     * @access private
     */	
	
	var $additionalColumns = array();


	/**
     * If set to true, the additional columns will display before the database columns.
     *
     * @var bool $additionalColumnsFirst
     * @access private
     * @deprecated
     * @todo Can this be deleted?
     */	

	var $additionalColumnsFirst = false;


	/**
     * This contains placement information for additional columns.
     *
     * @var array $additionalColumnsBefore
     * @access private
     */	

	var $additionalColumnsBefore = array();
	

	/**
     * Determines whether the user should be able to view records
     *
     * @var bool $allowView
     * @access private
     */	

	var $allowView;


	/**
     * Determines whether the user should be able to edit records
     *
     * @var bool $allowEdit
     * @access private
     */	

	var $allowEdit;


	/**
     * Determines whether the user should be able to delete records
     *
     * @var bool $allowDelete
     * @access private
     */	

	var $allowDelete;
	

	/**
     * The name of one column which can also be clicked for the view action.
     *
     * @var string $viewableColumn
     * @access private
     */	

	var $viewableColumn;


	/**
     * The name of one column which can also be clicked for the edit action.
     *
     * @var string $editableColumn
     * @access private
     */	

	var $editableColumn;
	

	/**
     * The display name for the summary.
     *
     * @var string $displayName
     * @access private
     */	

	var $displayName = 'Record';


	/**
     * The plural display name for the summary (ie. when there are more than 1 record).
     *
     * @var string $pluralDisplayName
     * @access private
     */	

	var $pluralDisplayName = 'Records';


	/**
     * An array of buttons belonging to the summary.
     *
     * @var array $buttons
     * @access private
     */	
	
	var $buttons = array();
	

	/**
     * The class to use to process add clicks.
     *
     * @var string $addHandlerClass
     * @access private
     */	

	var $addHandlerClass;


	/**
     * The class to use to process view clicks.
     *
     * @var string $viewHandlerClass
     * @access private
     */	

	var $viewHandlerClass;


	/**
     * The class to use to process edit clicks.
     *
     * @var string $editHandlerClass
     * @access private
     */	

	var $editHandlerClass;


	/**
     * The class to use to process delete clicks.
     *
     * @var string $deleteHandlerClass
     * @access private
     */	

	var $deleteHandlerClass;
	
	
	/**
     * The window to use to for add clicks.
     *
     * @var string $addWindow
     * @access private
     */	

	var $addWindow;


	/**
     * The window to use to for view clicks.
     *
     * @var string $viewWindow
     * @access private
     */	

	var $viewWindow;
	
	
	/**
     * The window to use to for edit clicks.
     *
     * @var string $editWindow
     * @access private
     */	

	var $editWindow;
	
	
	/**
     * The window to use to for delete clicks.
     *
     * @var string $deleteWindow
     * @access private
     */	

	var $deleteWindow;
	
	
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
     * An array of checkboxes for multiple selections.
     *
     * @var array $multipleSelectionCheckboxes
     * @access private
     */	
     
	var $multipleSelectionCheckboxes = array();
	

	/**
     * An array of buttons and handlers that act on the multiply selected records. They are of the form: name, handler (ie. multipleSelectionsButtons['Delete Selected'] = CC_Delete_Multiple_Records_Handler];).
     *
     * @var array $multipleSelectionsButtons
     * @access private
     */	

	var $multipleSelectionsButtons;
	

	/**
     * Whether or not to include an Add button.
     *
     * @var bool $includeAddButton
     * @access private
     */	

	var $includeAddButton = false;


	/**
     * Whether or not to include a button for downloading a Zip file of the summary for summary columns only.
     *
     * @var bool $includeDownloadButton
     * @access private
     */	

	var $includeDownloadButton = false;


	/**
     * Whether or not to include a button for downloading a Zip file of the summary for all records and columns.
     *
     * @var bool $includeDownloadAllButton
     * @access private
     */	

	var $includeDownloadAllButton = false;
	
	
	/**
     * Whether or not to include a button for downloading a XLS spreadsheet file of the summary for all records and columns.
     *
     * @var bool $includeDownloadXLSButton
     * @access private
     */	

	var $includeDownloadXLSButton = false;
	
	
	/**
     * The button that takes you to the next page in the summary.
     *
     * @var CC_Button $nextButton
     * @access private
     */	

	var $nextButton;


	/**
     * The button that takes you to the previous page in the summary.
     *
     * @var CC_Button $previousButton
     * @access private
     */	

	var $previousButton;


	/**
     * The default number of rows per page.
     *
     * @var int $defaultNumRowsPerPage
     * @access private
     */	

	var $defaultNumRowsPerPage;


	/**
     * A CC_AutoSubmit_Select_Field for the number of result rows to display per page.
     *
     * @var CC_AutoSubmit_Select_Field $numberRowsPerPageList
     * @access private
     */	

	var $numberRowsPerPageList;


	/**
     * A CC_AutoSubmit_Select_Field that allows users to jump to a particular page in the summary.
     *
     * @var CC_AutoSubmit_Select_Field $jumpToPageList
     * @access private
     */	

	var $jumpToPageList;


	/**
     * The current summary page we are on.
     *
     * @var int $pageNumber
     * @access private
     */	

	var $pageNumber;


	/**
     * The total number of pages in the summary (calculated in the update method).
     *
     * @var int $numPages
     * @access private
     */	

	var $numPages;


	/**
     * The start row number of the current summary page.
     *
     * @var int $startRowNumber
     * @access private
     */	

	var $startRowNumber = 1;


	/**
     * The end row number of the current summary page.
     *
     * @var int $endRowNumber
     * @access private
     */	

	var $endRowNumber;

	
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
     * The epoch time of the last call to update().
     *
     * @var int $_lastUpdateTime
     * @access private
     */	

	var $_lastUpdateTime = 0;


	/**
     * The time in seconds for the update timeout.
     *
     * @var int $_updateTimeout
     * @access private
     * @see $_lastUpdateTime
     */	

	var $_updateTimeout = 2;

			
	/**
     * The error message.
     *
     * @var string $_errorMessage
     * @access private
     * @see setErrorMessage()
     */	

	var $_errorMessage;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Summary
	//-------------------------------------------------------------------

	/** 
	 * The CC_Summary initializes the basic summary parameters and calls the update() method to do some extra initialization.
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $query The query to use.
	 * @param string $mainTable The main table that the summary's records belong to.
	 * @param string $sortByColumn The column to use to initially sort the rows.
	 * @param bool $sortAscending Whether or not to sort the $sortByColumn in ascending order. Sorts descending if false.
	 * @param bool $allowView Whether or not users are allowed to view records.
	 * @param bool $allowEdit Whether or not users are allowed to edit records.
	 * @param bool $allowDelete Whether or not users are allowed to delete records.
	 * @param string $viewHandlerClass The name of the class to use for viewing records.
	 * @param string $editHandlerClass The name of the class to use for editing records.
	 * @param string $deleteHandlerClass The name of the class to use for deleting records.
	 * @param string $addHandlerClass The class to use for adding records.
	 * @param string $displayName The name to use for display to describe the contents of the summary. Defaults to 'Record'.
	 */

	function CC_Summary($name, $query, $mainTable = NULL, $sortByColumn = 'ID', $sortAscending = true, $allowView = false, $allowEdit = false, $allowDelete = false, $viewHandlerClass = 'CC_Summary_Record_Handler', $editHandlerClass = 'CC_Summary_Record_Handler', $deleteHandlerClass = 'CC_Summary_Record_Handler', $addHandlerClass = 'CC_Summary_Record_Handler', $displayName = 'Record')
	{
		$application = &$_SESSION['application'];

		global $ccContentBackgroundColour, $ccTitleBarColour, $ccRecordOddRowColour, $ccRecordEvenRowColour, $ccButtonBarRowColour, $ccRecordHighlightRowColour, $ccDefaultRecordsPerPage;
		
		$this->window = &$application->getCurrentWindow();
		
		$this->setName($name);
		
		$this->query = $query;
		$this->mainTable = $mainTable;
		
		$this->showIdColumn = false;
		
		$this->setSortByColumn($sortByColumn);
		
		if ($sortAscending)
		{
			$this->setSortByDirection('ASC');
		}
		else
		{
			$this->setSortByDirection('DESC');
		}
		
		$this->fieldManager = &$application->fieldManager;
		
		$this->allowView = $allowView;
		$this->allowEdit = $allowEdit;
		$this->allowDelete = $allowDelete;
		
		$this->viewHandlerClass		= $viewHandlerClass;
		$this->editHandlerClass		= $editHandlerClass;
		$this->deleteHandlerClass	= $deleteHandlerClass;
		$this->addHandlerClass		= $addHandlerClass;
		
		$this->addWindow = CC_FRAMEWORK_PATH . '/CC_Windows/CC_Add_Record_Window'; 
		$this->viewWindow = CC_FRAMEWORK_PATH . '/CC_Windows/CC_View_Record_Window'; 
		$this->deleteWindow = CC_FRAMEWORK_PATH . '/CC_Windows/CC_Delete_Confirm_Window'; 
		$this->editWindow = CC_FRAMEWORK_PATH . '/CC_Windows/CC_Edit_Record_Window'; 
		
		$this->addNewButton = &new CC_Image_Button('Add New', '/N2O/CC_Images/add.gif', 20, 20, 0);
		$this->addNewButton->setValidateOnClick(false);

		$this->refreshButton = &new CC_Image_Button('Refresh', '/N2O/CC_Images/refresh.gif', 20, 20, 0);
		$this->refreshButton->setValidateOnClick(false);
		$this->refreshButton->registerHandler(new CC_Summary_Refresh_Handler($this->name));
		
		// pagination code
		
		$this->numberRowsPerPageList = &new CC_AutoSubmit_Select_Field($this->name . '_NUM_ROWS', 'Records Per Page', false, (cc_is_int($ccDefaultRecordsPerPage) ? $ccDefaultRecordsPerPage : 5), '', array('5' => '5', '10' => '10', '25' => '25', '50' => '50', '100' => '100', 'All' => 'All'));
		$this->numberRowsPerPageList->registerHandler(new CC_Summary_Num_Rows_Handler($this->name));
		
		$this->setDefaultNumRowsPerPage($this->getNumRowsPerPage(), false);
		
		$this->jumpToPageList = &new CC_AutoSubmit_Select_Field($this->name . '_JUMP_TO_PAGE', 'Page', false, 1, '', array());
		$this->jumpToPageList->registerHandler(new CC_Summary_Jump_To_Page_Handler($this->name));

		$this->nextButton = &new CC_Image_Button('Next >>', '/N2O/CC_Images/next.gif', 20, 20, 0);
		$this->previousButton = &new CC_Image_Button('<< Previous', '/N2O/CC_Images/previous.gif', 20, 20, 0);
		
		$this->nextButton->registerHandler(new CC_Summary_Next_Handler($this->name));
		$this->previousButton->registerHandler(new CC_Summary_Previous_Handler($this->name));
		
		$this->nextButton->setValidateOnClick(false);
		$this->previousButton->setValidateOnClick(false);
		
		//update from cookie variables, if they exist
		$numRowsCookieName = session_name() . '_' . $this->name . '_NUMROWS';
		if (array_key_exists($numRowsCookieName, $_COOKIE))
		{	
			$this->numberRowsPerPageList->setValue($_COOKIE[$numRowsCookieName]);
		}
		
		$sortByColumnCookieName = session_name() . '_' . $this->name . '_SORTBY';
		if (array_key_exists($sortByColumnCookieName, $_COOKIE))
		{	
			$this->setSortByColumn($_COOKIE[$sortByColumnCookieName]);
		}
		
		$sortByDirectionCookieName = session_name() . '_' . $this->name . '_SORTBYDIR';
		if (array_key_exists($sortByDirectionCookieName, $_COOKIE))
		{
			$this->setSortByDirection($_COOKIE[$sortByDirectionCookieName]);
		}
		
		//set the default value for the download queries
		$this->downloadSummaryQuery = $query;
		
		$this->setPageNumber(1);

		$this->backgroundColour   = $ccContentBackgroundColour;	// the background colour
		$this->columnHeaderColour = $ccTitleBarColour;		// the colour of the header row
		$this->evenRowColour      = $ccRecordEvenRowColour;	// the colour of even rows
		$this->oddRowColour       = $ccRecordOddRowColour;	// the colour of odd rows
		$this->buttonBarColour    = $ccButtonBarRowColour;	// the colour of the button row
		$this->rowHighlightColour = $ccRecordHighlightRowColour; // the colour of the highlight shading
		
		$this->setStyle('ccSummary');
	}

	
	//-------------------------------------------------------------------
	// METHOD: registerFilter
	//-------------------------------------------------------------------
	
	/** 
	 * This method registers a CC_Summary_Filter class/subclass object with a particular column. This is so that data from a column can be processed and presented in any way one wants. If the filter passed in is not the right type of class, a fatal error will be triggered and execution of the script will stop.
	 *
	 * @access public
	 * @param string $column The name of the column to filter.
	 * @param CC_Summary_Filter $filter The CC_Summary_Filter subclass to use a filter.
	 */
	
	function registerFilter($column, &$filter)
	{
		if (verifyClassType($filter, 'CC_Summary_Filter'))
		{
			$this->columnFilters[$column] = $filter;
		}
	}

	
	//-------------------------------------------------------------------
	// METHOD: addColumn
	//-------------------------------------------------------------------
	
	/** 
	 * This method adds an additional, arbitrary column to a summary view. A "CC_Summary_Content_Provider" class is provided which describes the content for a given column. If $before is 'true', the column is displayed before the content columns.
	 *
	 * @access public
	 * @param string $columnTitle The name of the new column.
	 * @param CC_Summary_Content_Provider $contentProvider The CC_Summary_Content_Provider to use to generate the additional column.
	 * @param bool $before Whether to include the additional column before or after the content columns.
	 */
	 	
	function addColumn($columnTitle, &$contentProvider, $before = false)
	{
		$application = &$_SESSION['application'];
		
		if (!verifyClassType($contentProvider, 'CC_Summary_Content_Provider'))
		{
			trigger_error('CC_Summary->addColumn($columnTitle): The content provider was not a CC_Summary_Content_Provider.', E_USER_ERROR);
		}
		
		$this->additionalColumns[$columnTitle] = &$contentProvider;
		$this->additionalColumnsBefore[$columnTitle] = $before;
	}


	//-------------------------------------------------------------------
	// METHOD: setAdditionalColumnsFirst
	//-------------------------------------------------------------------
	
	/** 
	  * Passing true to this method will cause the additional columns to display before the SQL columns. The default is false.
	  *
	  * @access public
	  * @param bool $first Whether or not additional columns should be displayed before content columns.
	  * @deprecated
      * @todo Can this be deleted?
	  */
	
	function setAdditionalColumnsFirst($first)
	{
		$this->additionalColumnsFirst = $first;
	}


	//-------------------------------------------------------------------
	// METHOD: getAdditionalColumnRowHTML
	//-------------------------------------------------------------------
	
	/** 
	  * This method gets the HTML for the additional columns.
	  *
	  * @access private
	  * @param int $recordId The id of the record in the current row.
	  * @param bool $before Whether or not the column should be displayed before content columns.
	  * @see getRowHTML()
	  */

	function getAdditionalColumnRowHTML($recordId, $before = false, $row = null)
	{
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Iterate through the additional Columns
		//
		$rowHTML = '';
		
		if (sizeof($this->additionalColumns) > 0)
		{
			$keys = array_keys($this->additionalColumns);
			
			for ($i = 0; $i < sizeof($keys); $i++)
			{
				if ($this->additionalColumnsBefore[$keys[$i]] == $before)
				{
					$contentProvider = &$this->additionalColumns[$keys[$i]];
					$rowHTML .= '<td align="' . $contentProvider->alignment . '">' . $contentProvider->getHTML($recordId, $this->mainTable, $row) . "</td>\n";
					
					unset($contentProvider);
				}
			}
		}
		
		return $rowHTML;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getIncludeMultipleSelectionsHTML
	//-------------------------------------------------------------------

	/** 
	  * This method gets the HTML for checkboxes used for multiple selection handling.
	  *
	  * @access private
	  * @param int $recordId The id of the record in the current row.
	  * @return string The HTML for the cell containing the multiple selections checkbox.
	  * @see getHTML()
	  */
	
	function getIncludeMultipleSelectionsHTML($recordId)
	{
		if (array_key_exists($recordId, $this->multipleSelectionCheckboxes))
		{
			$checkBoxField = &$this->multipleSelectionCheckboxes[$recordId];
		}
		else
		{
			$this->multipleSelectionCheckboxes[$recordId] = new CC_Checkbox_Field($this->name . '_ms_' . $recordId, '');
			$this->window->registerComponent($this->multipleSelectionCheckboxes[$recordId]);
		}
		
		$html = '   <td align="center" class="ccSummaryHeadings">';
		$html .= $this->multipleSelectionCheckboxes[$recordId]->getHTML() . "</td>\n";

		return $html;
	}

	
	//-------------------------------------------------------------------
	// METHOD: getIncludeMultipleSelectionsSelectAllJavascript
	//-------------------------------------------------------------------
	
	/** 
	  * This method returns the javascript for the select all checkbox that heads the column of checkboxes used for multiple selections.
	  *
	  * @access private
	  * @return string The Javascript code to include in the HTML.
	  * @see getHTML() 
	  */

	function getIncludeMultipleSelectionsSelectAllJavascript()
	{
	
$javascript = <<<EOF

<script language="JavaScript">
<!--
function SelectAll(summaryName)
{
	for (var i = 0; i < document.forms[0].elements.length; i++)
	{
		if (document.forms[0].elements[i].name.indexOf(summaryName + "_ms") != -1)
		{
			selectAllButton = document.forms[0].elements["sa_" + summaryName];
			document.forms[0].elements[i].checked = selectAllButton.checked;
		}
	}
}	//-->
</script>
EOF;

		return $javascript;
	}

	
	//-------------------------------------------------------------------
	// METHOD: getIncludeMultipleSelectionsSelectAllHTML
	//-------------------------------------------------------------------
	
	/** 
	  * This method returns the HTML for the select all checkbox that heads the column of checkboxes used for multiple selections.
	  *
	  * @access private
	  * @return string The Javascript code to include in the HTML.
	  * @see getHTML() 
	  */
	  
	function getIncludeMultipleSelectionsSelectAllHTML()
	{
		if (!isset($this->multipleSelectionSelectAllButton))
		{
			$this->multipleSelectionSelectAllButton = new CC_Button('Select All'); 
			$this->multipleSelectionSelectAllButton->registerHandler(new CC_Select_All_Checkboxes_Handler($this));		
			$this->window->registerComponent($this->multipleSelectionSelectAllButton);
		}
		
		$html = '   <td align="center" class="ccSummaryHeadings">';
		//$html .= $this->multipleSelectionSelectAllButton->getHTML() . "</td>\n";
		
		$summaryName = $this->name;

		$html .= '<input type="checkbox" name="sa_' . $this->name . '" onClick="SelectAll(\'' . $this->name . '\');" title="Select or de-select all messages"></td>' . "\n";

		return $html;
	}


	//-------------------------------------------------------------------
	// METHOD: getAdditionalColumnHTML
	//-------------------------------------------------------------------
	
	/** 
	  * This method returns the HTML for the additional columns.
	  *
	  * @access private
	  * @param bool $before Whether or not to show the before columns or the after columns.
	  * @return string The HTML for the additional column.
	  * @see getHTML()
	  */

	function getAdditionalColumnHTML($before = false)
	{
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Iterate through the additional Columns
		//
		$html = '';
		
		if (sizeof($this->additionalColumns) > 0)
		{
			$keys = array_keys($this->additionalColumns);
			
			for ($j = 0; $j < sizeof($keys); $j++)
			{
				if ($this->additionalColumnsBefore[$keys[$j]] == $before)
				{
					$contentProvider = &$this->additionalColumns[$keys[$j]];
					$html .= '   <td align="' . $contentProvider->alignment . '" class="ccSummaryHeadings">' . $contentProvider->getHeading($keys[$j]) . "</td>\n";
					
					unset($contentProvider);
				}
			}
		}

		return $html;
	}


	//-------------------------------------------------------------------
	// METHOD: getRowHTML()
	//-------------------------------------------------------------------

	/** 
	  * This method returns the HTML for the given row number.
	  *
	  * @access private
	  * @param int $rowNumber The row number to display.
	  * @param string $shade The background colour HTML.
	  * @return string The HTML for the given row.
	  * @see getHTML()
	  */

	function getRowHTML($rowNumber, $shade)
	{
		$application = &$_SESSION['application'];
		
		$rowHTML = '  <tr' . $shade . ' id="r' . $this->name . $rowNumber . '" valign="top" onMouseOver="obj=document.getElementById(\'r' . $this->name . $rowNumber . '\'); obj.style.backgroundColor=\'' . $this->rowHighlightColour . '\'; return true" onMouseOut="obj=document.getElementById(\'r' . $this->name . $rowNumber . '\'); obj.style.backgroundColor=\'\'; return true" class="ccSummaryData">' . "\n";
		
		$row = $this->rows[$rowNumber];
		
		if (array_key_exists($this->_idColumn, $row))
		{
			$recordId = $row[$this->_idColumn];
		}
		else if (array_key_exists(strtolower($this->_idColumn), $row))
		{
			$recordId = $row[strtolower($this->_idColumn)];
		}
		else
		{
			trigger_error('You didn\'t specify the ID field in your summary query! (Row: ' . $rowNumber . ') Are you using a GROUP BY clause? If so, you should use a CC_Safe_Summary instead.', E_USER_WARNING);
		}
		
		if (sizeof($this->multipleSelectionsButtons) > 0)
		{	
			$rowHTML .= $this->getIncludeMultipleSelectionsHTML($recordId);
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Iterate through the additional Columns
		//
		$rowHTML .= $this->getAdditionalColumnRowHTML($recordId, true, $row);


		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Iterate through the SQL results
		//
		
		//display the column ID only if the showIdColumn is true
		for ($l = 0; $l < sizeof($row); $l++)
		{
			if (!(!strcasecmp($this->columnNames[$l], $this->_idColumn) && ($this->showIdColumn == false)))
			{
				if (array_key_exists($this->columnNames[$l], $this->columnFilters))
				{
					$filter = &$this->columnFilters[$this->columnNames[$l]];

					if ($this->columnNames[$l] == $this->viewableColumn)
					{
						$viewButton = &$this->getViewEditDeleteLinkButton($this->viewHandlerClass, $recordId, 'View', $recordId . '_cv');
						$viewButton->setLabel($filter->processValue($row[$this->columnNames[$l]], $recordId, $row));

						$rowHTML .= '   <td align="' . $filter->alignment . '">' . $viewButton->getHTML() . "</td>\n";
					}
					else
					{
						$rowHTML .= '   <td align="' . $filter->alignment . '">' . $filter->processValue($row[$this->columnNames[$l]], $recordId, $row) . "</td>\n";
					}
				}
				else
				{
					if ($this->columnNames[$l] == $this->viewableColumn)
					{
						$viewButton = &$this->getViewEditDeleteLinkButton($this->viewHandlerClass, $recordId, 'View', $recordId . '_cv');
						$viewButton->setLabel($row[$this->columnNames[$l]]);
						
						$rowHTML .= '   <td>' . $viewButton->getHTML() . "</td>\n";
					}
					else
					{
						$rowHTML .= '   <td>' . $row[$this->columnNames[$l]] . "</td>\n";
					}
				}
			}
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Iterate through the additional Columns
		//
		$rowHTML .= $this->getAdditionalColumnRowHTML($recordId, false, $row);


		if ($this->allowView)
		{
			$viewButton = &$this->getViewEditDeleteLinkButton($this->viewHandlerClass, $recordId, 'View', $recordId . '_v', true);
			$rowHTML .= '   <td align="center">' . $viewButton->getHTML() . '</td>' . "\n";
			
			unset($viewButton);
		}
		
		
		if ($this->allowEdit)
		{
			$editButton = &$this->getViewEditDeleteLinkButton($this->editHandlerClass, $recordId, 'Edit', $recordId . '_e', true);
			$rowHTML .= '   <td align="center">' . $editButton->getHTML() . '</td>' . "\n";
			
			unset($editButton);
		}
		
		if ($this->allowDelete)
		{
			$deleteButton = &$this->getViewEditDeleteLinkButton($this->deleteHandlerClass, $recordId, 'Delete', $recordId . '_d', true);
			$rowHTML .= '   <td align="center">' . $deleteButton->getHTML() . '</td>' . "\n";
			
			unset($deleteButton);
		}
		
		$rowHTML .= "  </tr>\n";
		
		unset($row);
		
		return $rowHTML;
	}


	//-------------------------------------------------------------------
	// METHOD: getStatusRow()
	//-------------------------------------------------------------------
	
	/** 
	  * This method displays the status row which includes the previous button, add record button, next button, and refresh button.
	  *
	  * @access private
	  * @see getHTML()
	  */

	function getStatusRow()
	{
		echo ' <div align="center"><table border="0" cellspacing="0" cellpadding="0" class="ccSummaryStatus">' . "\n";
		echo '  <tr valign="middle" align="center">' . "\n";
		echo '   <td>' . $this->getPreviousButtonHTML() . "</td>\n";
		
		echo '   <td>' . "\n";
		
		if ($this->getEndRowNumber() == $this->getStartRowNumber())
		{
			echo '&nbsp;&nbsp;Displaying ' . $this->displayName . ' <b>' . $this->getStartRowNumber() . ' of ' . $this->numRecords . '</b>&nbsp;&nbsp;';
		}
		else
		{
			echo '&nbsp;&nbsp;Displaying ' . $this->pluralDisplayName . ' <b>' . $this->getStartRowNumber() . ' - ' . $this->getEndRowNumber() . '</b> of ' . $this->numRecords . '&nbsp;&nbsp;';
		}
		
		echo '   </td>' . "\n";
		
		
		echo '   <td>' . $this->refreshButton->getHTML() . "</td>\n";
		
		if ($this->includeAddButton)
		{
			echo '   <td>' . $this->addNewButton->getHTML() . "</td>\n";
		}

		echo '   <td>' . $this->getNextButtonHTML() . "</td>\n";

		echo '  </tr>' . "\n";
		echo ' </table></div>' . "\n";
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getSummaryControlRow()
	//-------------------------------------------------------------------
	
	/** 
	  * This method displays the summary control row which includes the change page number and change number of records per summary.
	  *
	  * @access private
	  * @see getHTML()
	  */

	function getSummaryControlRow()
	{
		if ($this->numRecords > $this->defaultNumRowsPerPage)
		{
			echo ' <table border="0" cellspacing="' . $this->cellspacing . '" cellpadding="' . $this->cellpadding . '" width="100%" class="ccSummaryControl">' . "\n";
	
			echo '  <tr bgcolor="' . $this->backgroundColour . '">' . "\n";
							
			echo '<td>' .  $this->jumpToPageList->getLabel() . ': ' . $this->jumpToPageList->getHTML() . '</td>';
			echo '<td align="right">' . $this->pluralDisplayName . ' Per Page: ' . $this->numberRowsPerPageList->getHTML() . ' </td>';

			echo "  </tr></table>\n";
		}
		
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getExtraSummaryButtons()
	//-------------------------------------------------------------------
	
	/** 
	  * This method displays the 'download', 'download ALL' and multiple selection buttons.
	  *
	  * @access private
	  * @see getHTML()
	  */

	function getExtraSummaryButtons()
	{
		if (sizeof($this->multipleSelectionsButtons) > 0)
		{
			echo '<div align="left">' . "\n";
			
			for ($i = 0; $i < sizeof($this->multipleSelectionsButtons); $i++)
			{
				$multipleSelectionsButton = &$this->multipleSelectionsButtons[$i];
				echo $multipleSelectionsButton->getHTML() . '&nbsp;';
			}
			
			echo '</div>' . "\n";
		}
		
		if (($this->includeDownloadButton) || ($this->includeDownloadAllButton) || ($this->includeDownloadXLSButton))
		{
			echo '<div align="center" class="ccSummaryDownload">' . "\n";
			
			if ($this->includeDownloadButton)
			{
				echo $this->downloadButton->getHTML() . '&nbsp;';
			}
			
			if ($this->includeDownloadAllButton)
			{
				echo $this->downloadAllButton->getHTML() . '&nbsp;';
			}

			if ($this->includeDownloadXLSButton)
			{
				echo $this->downloadXLSButton->getHTML();
			}
			echo '</div>' . "\n";
		}
	}


	//-------------------------------------------------------------------
	// METHOD: getHTML()
	//-------------------------------------------------------------------

	/** 
	  * This method is used in the window to return the HTML for the CC_Summary.
	  *
	  * @access public
	  * @return string The CC_Summary's display HTML.
	  */

	function getHTML()
	{
		$application = &$_SESSION['application'];
						
		if ($this->numRecords > 0 && !$this->hasErrorMessage())
		{
			$numColumns = sizeof($this->additionalColumns) + sizeof($this->columnNames);
			if ($this->allowView) $numColumns++;
			if ($this->allowEdit) $numColumns++;
			if ($this->allowDelete) $numColumns++;
			if (sizeof($this->multipleSelectionsButtons) > 0) $numColumns++;
			
			// the previous/next/refresh/add buttons.
			$this->getStatusRow();
			
			// page number/records per page
			$this->getSummaryControlRow();
			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// add Javascript for the multiple selection checkbox
			//
			
			if (sizeof($this->multipleSelectionsButtons) > 0)
			{
				echo $this->getIncludeMultipleSelectionsSelectAllJavascript();
			}

			echo '<table border="0" cellspacing="' . $this->cellspacing . '" cellpadding="' . $this->cellpadding . '" class="' . $this->style . '">' . "\n";
			
			// the column headers
			echo '  <tr bgcolor="' . $this->columnHeaderColour . '" class="ccSummaryHeadings">' . "\n";
			
			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// add the multiple selection checkbox
			//
			if (sizeof($this->multipleSelectionsButtons) > 0)
			{	
				echo $this->getIncludeMultipleSelectionsSelectAllHTML();
			}


			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Iterate through the additional Columns
			//
			echo $this->getAdditionalColumnHTML(true);


			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Iterate through the SQL results
			//
			for ($i = 0; $i < sizeof($this->columnNames); $i++)
			{
				//display the column ID only if the showIdColumn is true
				if (!(!strcasecmp($this->columnNames[$i], $this->_idColumn) && ($this->showIdColumn == false)))
				{				
					$displayName = $application->fieldManager->getDisplayName($this->columnNames[$i]);
					
					// if button exists!
					if (array_key_exists($displayName, $this->sortButtons))
					{
						$button = &$this->sortButtons[$displayName];
					}
					else
					{
						$sortHandler = &new CC_Summary_Sort_Handler($this, $this->columnNames[$i]);
						
						$button = &new CC_Sort_Button($displayName, $sortHandler);
						
						$sortHandler->setSortButton($button);
						
						//$button->registerHandler($sortHandler);
						
						$this->window->registerButton($button);
						$this->sortButtons[$displayName] = &$button;
					}
				
					if (array_key_exists($this->columnNames[$i], $this->columnFilters))
					{
						$filter = &$this->columnFilters[$this->columnNames[$i]];
						
						if ($this->columnNames[$i] == $this->sortByColumn)
						{
							$button->setCurrentlySorting(true);
							
							if ($this->sortByDirection == 'ASC')
							{
								$button->setAscending();
							}
							else
							{
								$button->setDescending();
							}
						}
						else
						{
							$button->setCurrentlySorting(false);
						}

						echo '   <td align="' . $filter->alignment . '">' . $button->getHTML();
					}
					else
					{
						if ($this->columnNames[$i] == $this->sortByColumn)
						{
							$button->setCurrentlySorting(true);

							if ($this->sortByDirection == 'ASC')
							{
								$button->setAscending();
							}
							else
							{
								$button->setDescending();
							}
						}
						else
						{
							$button->setCurrentlySorting(false);
						}
						
						echo'   <td>' . $button->getHTML();
					}
					
					echo "</td>\n";
					
					unset($button);
					unset($sortHandler);
				}
			}
			

			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Iterate through the additional Columns
			//
			echo $this->getAdditionalColumnHTML(false);

			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Add view/edit/delete if appropriate
			//
			if ($this->allowView)
			{
				echo '    <td align="center" class="ccSummaryHeadings">View</td>' . "\n"; 
			}
			if ($this->allowEdit)
			{
				echo '    <td align="center" class="ccSummaryHeadings">Edit</td>' . "\n"; 
			}
			if ($this->allowDelete)
			{
				echo '    <td align="center" class="ccSummaryHeadings">Delete</td>' . "\n"; 
			}
			
			echo "  </tr>\n";
			
			$shadeRow = FALSE;
			
			for ($k = 0; $k <= ($this->getEndRowNumber() - $this->getStartRowNumber()); $k++)
			{
				if ($shadeRow)
				{
					$shade = " bgcolor=\"" . $this->oddRowColour . "\"";
				}
				else
				{
					$shade = " bgcolor=\"" . $this->evenRowColour . "\"";
				}
								
				echo $this->getRowHTML($k, $shade);
				
				$shadeRow = !$shadeRow;
			}
			
			echo "  </table>\n";
			
			
			if ($this->numRecords > 25 && $this->getNumRowsPerPage() > 25)
			{
				// the previous/next/refresh/add buttons.
				$this->getStatusRow();
			}
			
			// get the download/download All and multiple select buttons if they are
			// included.
			$this->getExtraSummaryButtons();
		}
		// there are no records
		else
		{
			echo ' <table border="0" cellspacing="0" cellpadding="5" class="' . $this->style . '">' . "\n";
			echo '  <tr align="center" valign="middle">' . "\n";

			if ($this->hasErrorMessage())
			{
				echo '   <td><span class="ccError">' .  $this->getErrorMessage() . '</span></td>' . "\n";
			}
			else
			{
				echo '   <td>No ' .  $this->pluralDisplayName . ' found. &nbsp;</td>' . "\n";
				echo '   <td>' . $this->refreshButton->getHTML() . '</td>' . "\n";

				if ($this->includeAddButton)
				{	
					echo '   <td>' . $this->addNewButton->getHTML() . '</td>' . "\n";
					
				}
			}

			
			echo '  </tr>' . "\n";
			echo ' </table>' . "\n";
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getRawSummary()
	//-------------------------------------------------------------------

	/** 
	  * This method is used in the CC_Download_ZIP_Summary_Handler to return the raw tab-delimited and by CC_Download_XLS_Summary_Handler to return a raw array to build and XLS file.
	  *
	  * @access public
	  * @param string $downloadQuery The query to use for the download.
	  * @param boolean $getTabDelimitedText Output tab-delimited text if true, output an array otherwise.
	  * @return mixed Either the CC_Summary's tab-delimited text representation, or an Array which can be parsed to create to create a spreadsheet or further manipulate the data.
	  */

	function getRawSummary($downloadQuery, $getTabDelimitedText = true)
	{
		$application = &$_SESSION['application'];
		
		if ($getTabDelimitedText)
		{
			$tabDelimitedText = '';
		}
		else
		{
			$rawData = array();
		}
		
		if (($this->sortByColumn != NULL) && (!$this->downloadQueryComplete))
		{
			$downloadQuery .= ' order by ' . $this->sortByColumn . ' ' . $this->sortByDirection;
		}
		
		$results = $application->db->doSelect($downloadQuery);
		
		if (DB::isError($results))
		{
			$errorMessage = 'Error in query: ' . $results->getMessage() . ' query: ' . $downloadQuery;
			trigger_error($errorMessage, E_USER_WARNING);
			
			if ($getTabDelimitedText)
			{
				return $errorMessage;
			}
			else
			{	
				$rawData[0][0] = 'Error!';
				$rawData[0][1] = $errorMessage;
				return $rawData;
			}
		}
		
		$counter = 0;
		$rowNumber = 1;
		
		while ($row = cc_fetch_assoc($results))
		{
			if ($counter == 0)
			{
				$downloadColumnNames = array_keys($row);

				for ($i = 0; $i < sizeof($row); $i++)
				{
					//display the column ID only if the showIdColumn is true
					if ($downloadColumnNames[$i] == 'ID')
					{
						if ($getTabDelimitedText)
						{
							$tabDelimitedText .= "RECORD_ID\t";
						}
						else
						{
							$rawData[0][$i] = 'Record ID';
						}
					}
					else
					{				
						$displayName = $application->fieldManager->getDisplayName($downloadColumnNames[$i]);
						
						if ($getTabDelimitedText)
						{
							$tabDelimitedText .= $displayName . "\t";
						}
						else
						{
							$rawData[0][$i] = $displayName;
						}
					}
				}
				$counter++;
			}

			if (array_key_exists($this->_idColumn, $row))
			{
				$recordId = $row[$this->_idColumn];
			}
			else if (array_key_exists(strtolower($this->_idColumn), $row))
			{
				$recordId = $row[strtolower($this->_idColumn)];
			}
			
			for ($l = 0; $l < sizeof($row); $l++)
			{
				if (!strcasecmp($downloadColumnNames[$l], 'LAST_MODIFIED'))
				{
					if ($getTabDelimitedText)
					{
						$rowTabDelimitedText .= convertMysqlTimestampToHuman($row[$l] . "\t");
					}
					else
					{
						$rawData[$rowNumber][$downloadColumnNames[$l]] = convertMysqlTimestampToHuman($row[$downloadColumnNames[$l]]);
					}
				}
				else if (!strcasecmp($downloadColumnNames[$l], 'DATE_ADDED'))
				{
					if ($getTabDelimitedText)
					{
						$rowTabDelimitedText .= convertMysqlDateToSortable($row[$downloadColumnNames[$l]] . "\t");
					}
					else
					{
						$rawData[$rowNumber][$l] = convertMysqlDateToSortable($row[$downloadColumnNames[$l]]);
					}
				}
				else if (array_key_exists($downloadColumnNames[$l], $this->columnFilters))
				{
					$filter = &$this->columnFilters[$downloadColumnNames[$l]];
					if ($getTabDelimitedText)
					{
						$rowTabDelimitedText .= $filter->textFriendlyProcessValue($row[$downloadColumnNames[$l]], $recordId, $row) . "\t";
					}
					else
					{
						$rawData[$rowNumber][$l] =  $filter->textFriendlyProcessValue($row[$downloadColumnNames[$l]], $recordId, $row);
					}
				}
				else
				{	
					if ($getTabDelimitedText)
					{
						$rowTabDelimitedText .= $row[$downloadColumnNames[$l]] . "\t";
					}
					else
					{
						$rawData[$rowNumber][$l] = $row[$downloadColumnNames[$l]];
					}
				}
			}

			unset($row);
			
			$rowNumber++;
			
			if ($getTabDelimitedText)
			{
				$tabDelimitedText .= $rowTabDelimitedText . "\n";
				$rowTabDelimitedText = '';
			}
			
			unset($row);
		}
		
		unset($downloadColumnNames);
		
		if ($getTabDelimitedText)
		{
			return $tabDelimitedText;
		}
		else
		{
			return $rawData;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: showIdColumn()
	//-------------------------------------------------------------------

	/** 
	  * This method sets whether or not to show the summary's ID column.
	  *
	  * @access public
	  * @param bool $show Whether or not to show the ID column.
	  *	@deprecated
	  * @see setShowIdColumn().
	  */

	function showIdColumn($show)
	{
		trigger_error('showIdColumn() is deprecated. Use setShowIdColumn() instead.', E_USER_WARNING);
		$this->setShowIdColumn($show);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setShowIdColumn()
	//-------------------------------------------------------------------

	/** 
	  * This method sets whether or not to show the summary's ID column.
	  *
	  * @access public
	  * @param bool $show Whether or not to show the ID column.
	  */

	function setShowIdColumn($show)
	{
		$this->showIdColumn = $show;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDisplayName()
	//-------------------------------------------------------------------

	/** 
	  * This method sets the display names for the summary describing the records therein.
	  *
	  * @access public
	  * @param string $displayName The display name.
	  * @param string $pluralDisplayName The plural display name.
	  */

	function setDisplayName($displayName, $pluralDisplayName = '')
	{
		$this->displayName = $displayName;
		
		if ($pluralDisplayName == '')
		{
			$this->pluralDisplayName = $this->displayName . 's';
		}
		else
		{
			$this->pluralDisplayName = $pluralDisplayName;
		}
		
		if (!isset($this->downloadAllFileName))
		{
			$this->setDownloadAllFilename($this->pluralDisplayName);
		}
	}
		
	
	//-------------------------------------------------------------------
	// METHOD: update()
	//-------------------------------------------------------------------

	/** 
	  * This method initilaizes many of the parameters of the CC_Summary and syncronizes them with the database. It is called in the constructor and is triggered by hitting the summary's refresh button.
	  *
	  * @access public
	  */

	function update($force = false)
	{
		$this->clearErrorMessage();

		// Only do the update if it hasn't been done in the last two seconds...
		if ($force || (time() - $this->_lastUpdateTime) > $this->_updateTimeout)
		{
			$application = &$_SESSION['application'];
			
			$countQuery = 'select count(*) from ' . substr($this->query, strpos($this->query, 'from') + 5);
			
			$countResult = $application->db->doSelect($countQuery);
			
			if (PEAR::isError($countResult))
			{
				$this->setErrorMessage('Error while doing count query: ' . $countQuery . ' (' . $countResult->getMessage() . ')');
				return;
			}
			
			$countRow = cc_fetch_row($countResult);
			
			$this->numRecords = $countRow[0];
			
			//pagination updates
	
			// if there are fewer records than the starting record number, set page back to 1
			if ($this->numRecords < $this->getStartRowNumber())
			{
				$this->pageNumber = 1;
			}
			
			// update jump to page Auto-Submit Field 
			$this->updateJumpToPageList();
					
			// update start and end rows
			$this->updateStartRowNumber();
			$this->updateEndRowNumber();
			
			// update download summary query
			$this->downloadSummaryQuery = $this->query;

			unset($this->rows);
			$this->rows = array();
			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			
			$fullQuery = $this->query;
			
			if ($this->sortByColumn != NULL)
			{
				$fullQuery .= ' order by ' . $this->sortByColumn . ' ' .  $this->sortByDirection . ' limit ' .  $this->getNumRowsPerPage() . ' offset ' . ($this->getStartRowNumber() - 1);
			}
			
			$results = $application->db->doSelect($fullQuery);
			
			if (PEAR::isError($results))
			{
				$this->setErrorMessage('Error in query: ' . $fullQuery . ' (' . $results->getMessage() . ')');
				return;
			}
			
			$counter = 0;
			
			while ($row = cc_fetch_assoc($results))
			{
				if ($counter == 0)
				{
					$this->columnNames = array_keys($row);
					$counter++;
				}
	
				$this->rows[] = $row;
				
				unset($row);
			}
			
			//echo $fullQuery;
			
			unset($fullQuery);
			unset($counter);
	
			$this->_lastUpdateTime = time();
		}
		else
		{
			if (DEBUG)
			{
				trigger_error('Skipping update() because we updated less than ' . $this->_updateTimeout . ' seconds ago.', E_USER_WARNING);
			}
		}
	}

	
	//-------------------------------------------------------------------
	// METHOD: getNextButtonHTML()
	//-------------------------------------------------------------------

	/** 
	  * This method gets the HTML for the summary's next button.
	  *
	  * @access public
	  * @return string The HTML for the next button.
	  */

	function getNextButtonHTML()
	{
		if ($this->getEndRowNumber() != $this->numRecords)
		{
			return $this->nextButton->getHTML();	
		}
		else
		{
			return '<img src="/N2O/CC_Images/next.inactive.gif" width="20" height="20" border="0">';
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getPreviousButtonHTML()
	//-------------------------------------------------------------------

	/** 
	  * This method gets the HTML for the summary's previous button.
	  *
	  * @access public
	  * @return string The HTML for the previous button.
	  */

	function getPreviousButtonHTML()
	{
		if ($this->getPageNumber() != 1)
		{
			return $this->previousButton->getHTML();	
		}
		else
		{
			return '<img src="/N2O/CC_Images/previous.inactive.gif" width="20" height="20" border="0">';
		}

	}
	
	
	//-------------------------------------------------------------------
	// METHOD: updateJumpToPageList
	//-------------------------------------------------------------------
	
	/** 
	  * The method updates the Jump To Page number auto-submit list and is called by the update() method.
	  *
	  * @access private
	  * @see update()
	  */
	
	function updateJumpToPageList()
	{	
		$jumpToPageArray = array();
		
		for ($i=1; $i <= $this->getNumPages(); $i++)
		{
			$jumpToPageArray[] = $i;
		}
		
		$this->jumpToPageList->setOptions($jumpToPageArray);
		$this->jumpToPageList->setValue($this->getPageNumber());
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: updateStartRowNumber
	//-------------------------------------------------------------------
	
	/** 
	  * The method updates the start row number depending on the current page.
	  *
	  * @access private
	  * @see update()
	  */
	
	function updateStartRowNumber()
	{
		$this->setStartRowNumber(($this->getNumRowsPerPage() * ($this->getPageNumber() - 1) ) + 1);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getStartRowNumber
	//-------------------------------------------------------------------

	/** 
	  * The method gets the start row number on the current page.
	  *
	  * @access private
	  * @return int The start row number of the current page.
	  */	
	
	function getStartRowNumber()
	{
		return $this->startRowNumber;
	}


	//-------------------------------------------------------------------
	// METHOD: setStartRowNumber
	//-------------------------------------------------------------------

	/** 
	  * The method sets the start row number.
	  *
	  * @access private
	  * @param int The start row number to set.
	  */	
	
	function setStartRowNumber($startRowNumber)
	{
		$this->startRowNumber = $startRowNumber;
	}
		
	
	//-------------------------------------------------------------------
	// METHOD: getNumPages
	//-------------------------------------------------------------------

	/** 
	  * The method gets the number of pages in the summary based on $numRowsPerPage.
	  *
	  * @access protected
	  * @return int The number of pages in the summary.
	  */	
	
	function getNumPages()
	{
		if (($this->numRecords % $this->getNumRowsPerPage()) == 0)
		{
			if ($this->getNumRowsPerPage() != 0)
			{
				return ($this->numRecords / $this->getNumRowsPerPage());
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return ($this->numRecords / $this->getNumRowsPerPage()) + 1;	
		}
		
	}


	//-------------------------------------------------------------------
	// METHOD: getNumRows
	//-------------------------------------------------------------------

	/** 
	  * The method gets the number of rows in the summary.
	  *
	  * @access public
	  * @return int The number of rows in the summary.
	  */	
	
	function getNumRows()
	{
		return $this->numRecords;
	}
	


	//-------------------------------------------------------------------
	// METHOD: getEndRowNumber
	//-------------------------------------------------------------------
	
	/** 
	  * The method gets the end row number on the current summary page.
	  *
	  * @access protected
	  * @return int The end row number of the current summary page.
	  */	

	function getEndRowNumber()
	{
		return $this->endRowNumber;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: updateEndRowNumber
	//-------------------------------------------------------------------
	
	/** 
	  * The method gets the end row number on the current summary page.
	  *
	  * @access private
	  * @return int The end row number of the current summary page.
	  */	

	function updateEndRowNumber()
	{
		$endRowNumber = ($this->getNumRowsPerPage() * $this->getPageNumber());
		
		if ($endRowNumber > $this->numRecords)
		{
			$endRowNumber = $this->numRecords;
		}
		
		$this->endRowNumber = $endRowNumber;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getPageNumber
	//-------------------------------------------------------------------
	
	/** 
	  * The method gets the current summary page number.
	  *
	  * @access protected
	  * @return int The current summary page number.
	  */	

	function getPageNumber()
	{
		return $this->pageNumber;
	}


	//-------------------------------------------------------------------
	// METHOD: setPageNumber
	//-------------------------------------------------------------------
	
	/** 
	  * The method sets the current summary page number.
	  *
	  * @access private
	  * @param int The current summary page number to set.
	  */	

	function setPageNumber($pageToSetTo)
	{
		$this->pageNumber = $pageToSetTo;
	}

	
	//-------------------------------------------------------------------
	// METHOD: getNumRowsPerPage
	//-------------------------------------------------------------------
	
	/** 
	  * The method gets the current number of rows per page based on the summary's $numberRowsPerPageList CC_Auto_Submit_SelectList_Field.
	  *
	  * @access public
	  * @return int The currently selected number of rows per page preference.
	  */	

	function getNumRowsPerPage()
	{
		$selectListValue = $this->numberRowsPerPageList->getValue();
		
		if ($selectListValue == 'All')
		{
			return $this->numRecords;
		}
		else
		{
			return $selectListValue;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setNumRowsPerPage
	//-------------------------------------------------------------------
	
	/** 
	  * The method sets the current number of rows per page and updates the summary's $numberRowsPerPageList CC_Auto_Submit_SelectList_Field.
	  *
	  * @access private
	  * @param int $rowsPerPage The number of rows per page preference to set.
	  */	

	function setNumRowsPerPage($rowsPerPage)
	{
		$this->defaultNumRowsPerPage = $rowsPerPage;
		$this->numberRowsPerPageList->setValue($rowsPerPage);
		$this->updateEndRowNumber();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setSortByColumn
	//-------------------------------------------------------------------
	
	/** 
	  * The method sets the column to sort by in the summary.
	  *
	  * @access public
	  * @param string $sortByColumn The column to sort by.
	  */	

	function setSortByColumn($sortByColumn)
	{
		if (strstr($sortByColumn, ' '))
		{
			$this->sortByColumn = '\'' . $sortByColumn . '\'';
		}
		else
		{
			$this->sortByColumn = $sortByColumn;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setSortByDirection
	//-------------------------------------------------------------------
	
	/** 
	  * The method sets the direction to sort the $sortByColumn by.
	  *
	  * @access public
	  * @param string $sortByDirection The direction to sort by. Possible values are 'DESC' or 'ASC'.
	  */	

	function setSortByDirection($sortByDirection)
	{
		//echo "setting to $sortByDirection<br>";
		$this->sortByDirection = $sortByDirection;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDefaultNumRowsPerPage
	//-------------------------------------------------------------------
	
	/** 
	  * The method sets the direction to sort the $sortByColumn by.
	  *
	  * @access public
	  * @param string $sortByDirection The direction to sort by. Possible values are 'DESC' or 'ASC'.
	  */	

	function setDefaultNumRowsPerPage($defaultRowsPerPage, $update = true)
	{
		$options = &$this->numberRowsPerPageList->options;
		
		$options[$defaultRowsPerPage] = $defaultRowsPerPage;
		
		asort($options, SORT_NUMERIC);
		
		$numRowsCookieName = session_name() . '_' . $this->name . '_NUMROWS';

		if (!(array_key_exists($numRowsCookieName, $_COOKIE)))
		{
			$this->defaultNumRowsPerPage = $defaultRowsPerPage;
			$this->setNumRowsPerPage($this->defaultNumRowsPerPage);
		}
		
		if ($update)
		{
			$this->update();
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setIncludeAddButton
	//-------------------------------------------------------------------
	
	/**
	 * @deprecated See setAllowAdd() instead.
	 *
	 */
	
	function setIncludeAddButton($includeAdd)
	{
		trigger_error('CC_Summary::setIncludeAddButton() deprecated. Use setAllowAdd() instead.', E_USER_WARNING);
		$this->setAllowAdd($includeAdd);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setAllowAdd
	//-------------------------------------------------------------------
	
	/**
	 * This method sets whether or not to include the summary's add new record button.
	 *
	 * @access public
	 * @param bool $add Whether or not to include the add button.
	 */
	 
	function setAllowAdd($add)
	{
		$this->includeAddButton = $add;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: addMultipleSelectionsButton
	//-------------------------------------------------------------------
		
	// $multipleSelectHandler must be a subclass of CC_Multiple_Selections_Handler
		
	function addMultipleSelectionsButton($buttonText, &$multipleSelectHandler)
	{
		$multipleSelectionsButton = &new CC_Button($buttonText);

		$multipleSelectHandler->summaryName = $this->getName();
		$multipleSelectHandler->tableName = $this->mainTable;

		$multipleSelectionsButton->registerHandler($multipleSelectHandler);
		$this->multipleSelectionsButtons[] = &$multipleSelectionsButton;
		
		$this->window->registerComponent($multipleSelectionsButton);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setIncludeDownloadButton
	//-------------------------------------------------------------------
	
	/**
	 * This method sets whether or not to include the summary's $downloadButton button.
	 *
	 * @access public
	 * @param bool $includeDownload Whether or not to include the download button.
	 */

	function setIncludeDownloadButton($includeDownload = true)
	{
		$this->includeDownloadButton = $includeDownload;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setIncludeDownloadAllXLSButton
	//-------------------------------------------------------------------
	
	/**
	 * This method sets whether or not to include the summary's $downloadXLSButton button.
	 *
	 * @access public
	 * @param bool $includeDownloadXLS Whether or not to include the download all button.
	 */

	function setIncludeDownloadXLSButton($includeDownloadXLS = true)
	{
		$this->includeDownloadXLSButton = $includeDownloadXLS;
	}
		
	
	//-------------------------------------------------------------------
	// METHOD: setDownloadXLSHandler
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the handler activated by the summary's $downloadXLSButton button.
	 *
	 * @access public
	 * @param string The hander to use for the download XLS button.
	 */

	function setDownloadXLSHandler($downloadXLSHandler)
	{
		$this->downloadXLSHandler = $downloadXLSHandler;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDownloadTabHandler
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the handler activated by the summary's $downloadButton button.
	 *
	 * @access public
	 * @param bool $downloadTabHandler Whether or not to include the download tab-delimited button.
	 */

	function setDownloadTabHandler($downloadTabHandler)
	{
		$this->setDownloadTabHandler = $downloadTabHandler;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDownloadAllHandler
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the handler activated by the summary's $downloadAllButton button.
	 *
	 * @access public
	 * @param string The hander to use for the download download all button.
	 */

	function setDownloadAllHandler($downloadAllHandler)
	{
		$this->downloadAllHandler = $downloadAllHandler;
	}
	
		
	//-------------------------------------------------------------------
	// METHOD: setIncludeDownloadAllButton
	//-------------------------------------------------------------------
	
	/**
	 * This method sets whether or not to include the summary's $downloadAllButton button.
	 *
	 * @access public
	 * @param bool $includeDownloadAll Whether or not to include the download all button.
	 */

	function setIncludeDownloadAllButton($includeDownloadAll = true)
	{
		$this->includeDownloadAllButton = $includeDownloadAll;
	}
	
		
	//-------------------------------------------------------------------
	// METHOD: setDownloadAllFileName
	//-------------------------------------------------------------------

	/**
	 * This method sets download all file's file name.
	 *
	 * @access public
	 * @param string $downloadAllFileName The download all file's file name.
	 */
	
	function setDownloadAllFileName($downloadAllFileName)
	{
		$this->downloadAllFileName = $downloadAllFileName;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDownloadSummaryFileName
	//-------------------------------------------------------------------
	
	/**
	 * This method sets download file's file name.
	 *
	 * @access public
	 * @param string $downloadSummaryFileName The download file's file name.
	 */

	function setDownloadSummaryFileName($downloadSummaryFileName)
	{
		$this->downloadSummaryFileName = $downloadSummaryFileName;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDownloadAllQuery
	//-------------------------------------------------------------------
	
	/**
	 * This method sets download all query.
	 *
	 * @access public
	 * @param string $downloadAllQuery The download all query.
	 * @param bool $queryComplete Whether or not the passed query should remain as is (ie. an order by is added in getRawSummary()).
	 */

	function setDownloadAllQuery($downloadAllQuery, $queryComplete = false)
	{
		$this->downloadAllQuery = $downloadAllQuery;
		$this->downloadQueryComplete = $queryComplete;
	}


	//-------------------------------------------------------------------
	// METHOD: getDownloadAllQuery
	//-------------------------------------------------------------------
	
	/**
	 * This method gets the download all query, if it doesn't exist, it will just get the regular query
	 *
	 * @access public
	 * @param string $downloadAllQuery The download all query.
	 */

	function getDownloadAllQuery()
	{
		if (strlen($this->downloadAllQuery) > 0)
		{
			return $this->downloadAllQuery;
		}
		else
		{
			return $this->query;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setAddButtonHandler
	//-------------------------------------------------------------------
	
	/**
	* @deprecated See setAddHandler() instead.
	*
	*/
	
	function setAddButtonHandler(&$handler)
	{
		$this->setAddHandler($handler);
	}


	//-------------------------------------------------------------------
	// METHOD: setAddHandler
	//-------------------------------------------------------------------
		
	/**
	 * This method sets the summary's add handler.
	 *
	 * @access public
	 * @param CC_Action_Handler $handler The summary's add handler.
	 */

	function setAddHandler(&$handler)
	{
		$this->addNewButton->clearHandlers();
		$this->addNewButton->registerHandler($handler);
	}


	//-------------------------------------------------------------------
	// METHOD: setAllowView
	//-------------------------------------------------------------------
	
	/** 
	  * This method sets the inclusion of the 'View' button.
	  *
	  * @access public
	  * @param bool $allowView Whether or not to include view capabilities.
	  */
	
	function setAllowView($allowView)
	{
		if ($allowView === true)
		{
			$this->allowView = true;
		}
		else if ($allowView === false)
		{
			$this->allowView = false;
		}
		else
		{
			trigger_error('CC_Summary->setAllowView() was passed a variable that was not a boolean!', E_USER_ERROR);
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setAllowEdit
	//-------------------------------------------------------------------
	
	/** 
	  * This method sets the inclusion of the 'Edit' button.
	  *
	  * @access public
	  * @param bool $allowEdit Whether or not to include edit capabilities.
	  */
	
	function setAllowEdit($allowEdit)
	{
		if ($allowEdit === true)
		{
			$this->allowEdit = true;
		}
		else if ($allowEdit === false)
		{
			$this->allowEdit = false;
		}
		else
		{
			trigger_error('CC_Summary->setAllowEdit() was passed a variable that was not a boolean!', E_USER_ERROR);
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setAllowDelete
	//-------------------------------------------------------------------
	
	/** 
	  * This method sets the inclusion of the 'Delete' button.
	  *
	  * @access public
	  * @param bool $allowDelete Whether or not to include delete capabilities.
	  */
	
	function setAllowDelete($allowDelete)
	{
		if ($allowDelete === true)
		{
			$this->allowDelete = true;
		}
		else if ($allowDelete === false)
		{
			$this->allowDelete = false;
		}
		else
		{
			trigger_error('CC_Summary->setAllowDelete() was passed a variable that was not a boolean!', E_USER_ERROR);
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setViewHandler
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the summary's view handler.
	 *
	 * @access public
	 * @param string $handler The summary's CC_View_Record_Handler view handler.
	 * @param string $column An optional column name to use as clickable to view.
	 * @see CC_View_Record_Handler
	 */
	
	function setViewHandler($handler, $column = NULL)
	{
		$this->viewHandlerClass = $handler;
		
		if ($column != NULL)
		{
			$this->viewableColumn = $column;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: setViewableColumn
	//-------------------------------------------------------------------
	
	/** 
	  * This method sets the viewable column to be clickable by the 'View' button.
	  *
	  * @access public
	  * @param string $column The column name to use as clickable to view.
	  */
	
	function setViewableColumn($column)
	{
		$this->viewableColumn = $column;
	}


	//-------------------------------------------------------------------
	// METHOD: setEditHandler
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the summary's edit handler.
	 *
	 * @access public
	 * @param string $handler The summary's edit handler.
	 */
	
	function setEditHandler($handler)
	{
		$this->editHandlerClass = $handler;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setAddWindow
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the window the summary accesses for record additions.
	 *
	 * @access public
	 * @param string $targetWindow The path to the add window.
	 */
	
	function setAddWindow($targetWindow)
	{
		$this->addWindow = $targetWindow;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setEditWindow
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the window the summary accesses for record edits.
	 *
	 * @access public
	 * @param string $targetWindow The path to the edit window.
	 */
	
	function setEditWindow($targetWindow)
	{
		$this->editWindow = $targetWindow;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setDeleteWindow
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the window the summary accesses for record 
	 * deletion.
	 *
	 * @access public
	 * @param string $targetWindow The path to the delete window.
	 */
	
	function setDeleteWindow($targetWindow)
	{
		$this->deleteWindow = $targetWindow;
	}


	//-------------------------------------------------------------------
	// METHOD: setViewWindow
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the window the summary accesses for record views.
	 *
	 * @access public
	 * @param string $targetWindow The path to the views window.
	 */
	
	function setViewWindow($targetWindow)
	{
		$this->viewWindow = $targetWindow;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getAddWindow
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the window the summary accesses for record additions.
	 *
	 * @access public
	 * @return string The path to the add window.
	 */
	
	function getAddWindow()
	{
		return $this->addWindow;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getEditWindow
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the window the summary accesses for record edits.
	 *
	 * @access public
	 * @return string The path to the edit window.
	 */
	
	function getEditWindow()
	{
		return $this->editWindow;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: getDeleteWindow
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the window the summary accesses for record 
	 * deletion.
	 *
	 * @access public
	 * @return string The path to the delete window.
	 */
	
	function getDeleteWindow()
	{
		return $this->deleteWindow;
	}


	//-------------------------------------------------------------------
	// METHOD: getViewWindow
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the window the summary accesses for record views.
	 *
	 * @access public
	 * @return $targetWindow The path to the views window.
	 */
	
	function getViewWindow()
	{
		return $this->viewWindow;
	}


	//-------------------------------------------------------------------
	// METHOD: setDeleteHandler
	//-------------------------------------------------------------------
	
	/**
	 * This method sets the summary's delete handler.
	 *
	 * @access public
	 * @param string $handler The summary's delete handler.
	 */
	
	function setDeleteHandler($handler)
	{
		$this->deleteHandlerClass = $handler;

/*		if (is_a($handler, "CC_Action_Handler"))
		{
			$this->deleteHandlerClass = $handler;
		}
		else
		{
			trigger_error('CC_Summary->setDeleteHandler() was passed an object that was not a CC_Action_Handler!', E_USER_ERROR);
		}*/
	}


	//-------------------------------------------------------------------
	// METHOD: getViewEditDeleteLinkButton
	//-------------------------------------------------------------------

	/**
	 * This method returns buttons for view, edit and delete actions in the summary.
	 *
	 * @access protected
	 * @param CC_Action_Handler $handlerClass The handler to use for the button.
	 * @param int $recordId The record id of the record the button should act on.
	 * @param string $label The label to use for the button.
	 * @param string $key The unique key for the button.
	 * @param bool $image Whether or not to use an image for the button.
	 * @see getRowHTML()
	 */
	
	function &getViewEditDeleteLinkButton($handlerClass, $recordId, $label, $key, $image = false)
	{
		if (array_key_exists($key, $this->buttons))
		{
			$button = &$this->buttons[$key];

			// In case the column's data was updated, we should also update
			// the button label.
			$button->setLabel($label);
		}
		else
		{
			if ($image)
			{
				$button = &new CC_Image_Button($label, '/N2O/CC_Images/cc_summary.' . strtolower($label) . '.gif', 16, 18);
			}
			else
			{
				$button = &new CC_Text_Button($label);
			}
			
			$targetWindowMethod = 'get' . $label . 'Window';
			
			$button->registerHandler(new $handlerClass($this->mainTable, $recordId, $this->displayName, $this->$targetWindowMethod(), $label, $this->_idColumn));
			
			$button->setValidateOnClick(false);
			
			$this->buttons[$key] = &$button;
			
			$this->window->registerButton($button);
		}
		
		return $button;
	}


	//-------------------------------------------------------------------
	// METHOD: set_cellpadding
	//-------------------------------------------------------------------
	
	/** 
	  * This method sets the cell padding for the summary's table HTML.
	  * 
	  * @access public
	  * @param int The cell padding to use.
	  */

	function set_cellpadding($cellpadding)
	{
		$this->cellpadding = $cellpadding;
	}


	//-------------------------------------------------------------------
	// METHOD: set_cellspacing
	//-------------------------------------------------------------------
	
	/** 
	  * This method sets the cell spacing for the summary's table HTML.
	  * 
	  * @access public
	  * @param int The cell spacing to use.
	  */

	function set_cellspacing($cellspacing)
	{
		$this->cellspacing = $cellspacing;
	}


	//-------------------------------------------------------------------
	// METHOD: setIdColumn
	//-------------------------------------------------------------------
	
	/** 
	  * This method sets the column name of the "ID" column. By default,
	  * it is "ID".
	  * 
	  * @access public
	  * @param string The column name.
	  */

	function setIdColumn($idColumn, $showIdColumn = false)
	{
		$this->_idColumn = $idColumn;
		$this->setShowIdColumn($showIdColumn);
	}


	//-------------------------------------------------------------------
	// METHOD: setUpdateTimeout
	//-------------------------------------------------------------------
	
	/** 
	  * This method sets the timeout after which a call to update() will
	  * go through. By default, if two calls to update() are made less
	  * than or equal to 2 seconds, the second call will be skipped.
	  * 
	  * @access public
	  * @param int $timeout The timeout in seconds
	  */

	function setUpdateTimeout($timeout)
	{
		$this->_updateTimeout = (int)$timeout;
	}


	//-------------------------------------------------------------------
	// METHOD: setErrorMessage()
	//-------------------------------------------------------------------
	
	/** 
	  * This method sets an error message to display for the summary. It
	  * gets used by update() if there is a problem with the query.
	  * 
	  * @access public
	  * @param string $message The error message
	  */

	function setErrorMessage($message)
	{
		$this->_errorMessage = $message;
	}


	//-------------------------------------------------------------------
	// METHOD: getErrorMessage()
	//-------------------------------------------------------------------
	
	/** 
	  * This method gets the error message.
	  * 
	  * @access public
	  * @param string $message The error message
	  */

	function getErrorMessage()
	{
		return $this->_errorMessage;
	}


	//-------------------------------------------------------------------
	// METHOD: clearErrorMessage()
	//-------------------------------------------------------------------
	
	/** 
	  * This method clears the error message.
	  * 
	  * @access public
	  */

	function clearErrorMessage()
	{
		unset($this->_errorMessage);
	}


	//-------------------------------------------------------------------
	// METHOD: hasErrorMessage()
	//-------------------------------------------------------------------
	
	/** 
	  * This method checks to see if there is an error message.
	  * 
	  * @access public
	  * @return boolean
	  */

	function hasErrorMessage()
	{
		return isset($this->_errorMessage);
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
		$window->registerSummary($this);
		
		if (!sizeof($this->addNewButton->handlers))
		{
			$this->addNewButton->registerHandler(new $this->addHandlerClass($this->mainTable, -1, $this->displayName, $this->addWindow, 'Add'));
		}

		$window->registerComponent($this->previousButton);
		$window->registerComponent($this->refreshButton);
		$window->registerComponent($this->addNewButton);
		$window->registerComponent($this->nextButton);
		$window->registerComponent($this->jumpToPageList);
		$window->registerComponent($this->numberRowsPerPageList);

		if ($this->includeDownloadButton)
		{
			$this->downloadButton = &new CC_Text_Button('Download All');
			$this->downloadButton->registerHandler(new $this->downloadAllHandler($this, $this->downloadSummaryQuery, $this->downloadSummaryFileName));
			
			$this->downloadButton->setValidateOnClick(false);
			$this->downloadButton->setFieldUpdater(false);

			$window->registerComponent($this->downloadButton);
		}
		
		if ($this->includeDownloadAllButton)
		{
			$this->downloadAllButton = &new CC_Text_Button('Download Tab-Delimited File');
			$this->downloadAllButton->registerHandler(new $this->downloadTabHandler($this, $this->getDownloadAllQuery(), $this->downloadAllFileName));
			
			$this->downloadAllButton->setValidateOnClick(false);
			$this->downloadAllButton->setFieldUpdater(false);

			$window->registerComponent($this->downloadAllButton);
		}

		if ($this->includeDownloadXLSButton)
		{
			$this->downloadXLSButton = &new CC_AnchorImage_Button('Download Excel Spreadsheet', '/N2O/CC_Images/cc_summary.excel.png', 96, 40);
			$this->downloadXLSButton->setPngFix(true);
			$this->downloadXLSButton->registerHandler(new $this->downloadXLSHandler($this));
			$this->downloadXLSButton->setValidateOnClick(false);
			$this->downloadXLSButton->setFieldUpdater(false);

			$window->registerComponent($this->downloadXLSButton);
		}

		// Defer the update until the component is registered...
		$this->update();
	}
}

?>