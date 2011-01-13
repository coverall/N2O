<?php
// $Id: CC_Dynamic_Summary.php,v 1.15 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Dynamic_Summary
//=======================================================================

/**
 * This class provides a dynamic way to use a CC_Summary.
 *
 * @package CC_Summaries
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2008, Coverall Crew
 */
	 
class CC_Dynamic_Summary extends CC_Summary
{

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

	function CC_Dynamic_Summary($name, $query, $mainTable = NULL, $sortByColumn = 'ID', $sortAscending = true, $allowView = false, $allowEdit = false, $allowDelete = false, $viewHandlerClass = 'CC_Summary_Record_Handler', $editHandlerClass = 'CC_Summary_Record_Handler', $deleteHandlerClass = 'CC_Summary_Record_Handler', $addHandlerClass = 'CC_Summary_Record_Handler', $displayName = 'Record')
	{
		parent::CC_Summary($name, $query, $mainTable, $sortByColumn, $sortAscending, $allowView, $allowEdit, $allowDelete, $viewHandlerClass, $editHandlerClass, $deleteHandlerClass, $addHandlerClass, $displayName);

		$this->nextButton->setOnClick("nextPage('" . $this->getName() . "'); return false;");
		$this->nextButton->setId($this->getName() . '_summaryNext');
		$this->previousButton->setOnClick("previousPage('" . $this->getName() . "'); return false;");
		$this->previousButton->setId($this->getName() . '_summaryPrevious');
	}


	//-------------------------------------------------------------------
	// METHOD: getRowsAsJson()
	//-------------------------------------------------------------------

	/** 
	  * Get the rows as Json
	  *
	  * @access public
	  * @return string The CC_Summary's results as Json.
	  */

	function getRowsAsJson($getControlRow = false)
	{
		$result = array();
		$result['success'] = true;
		$result['startRow'] = $this->getStartRowNumber();
		$result['endRow'] = $this->getEndRowNumber();
		$result['records'] = $this->numRecords;
		$result['nextButton'] = $this->getNextButtonHTML();
		$result['previousButton'] = $this->getPreviousButtonHTML();
		$result['direction'] = $this->getSortByDirection();
		$result['pluralDisplayName'] = $this->pluralDisplayName;
		
		if ($getControlRow)
		{
			$result['controlRow'] = $this->getSummaryControlRow();
		}
		else
		{
			$result['controlRow'] = false;
		}


		for ($i = 0; $i < sizeof($this->rows); $i++)
		{
			$row = $this->rows[$i];
	
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
				trigger_error($this->getName() . ' summary: You didn\'t specify the ID field in your summary query! (Row: ' . $rowNumber . ') Are you using a GROUP BY clause? If so, you should use a CC_Safe_Summary instead.', E_USER_WARNING);
			}


			if (sizeof($this->additionalColumns) > 0)
			{
				$keys = array_keys($this->additionalColumns);
				
				for ($k = 0; $k < sizeof($keys); $k++)
				{
					if ($this->additionalColumnsBefore[$keys[$k]] == true)
					{
						$contentProvider = &$this->additionalColumns[$keys[$k]];
						
						$result['rows'][$recordId][] = $contentProvider->getHTML($recordId, $this->mainTable, $row);
						
						unset($contentProvider);
					}
				}
			}


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
	
							$result['rows'][$recordId][] = $viewButton->getHTML();
						}
						else
						{
							$result['rows'][$recordId][] = $filter->processValue($row[$this->columnNames[$l]], $recordId, $row);
						}
					}
					else
					{
						if ($this->columnNames[$l] == $this->viewableColumn)
						{
							$viewButton = &$this->getViewEditDeleteLinkButton($this->viewHandlerClass, $recordId, 'View', $recordId . '_cv');
							$viewButton->setLabel($row[$this->columnNames[$l]]);
							
							$result['rows'][$recordId][] = $viewButton->getHTML();
						}
						else
						{
							$result['rows'][$recordId][] = $row[$this->columnNames[$l]];
						}
					}
				}
			}
	

			if (sizeof($this->additionalColumns) > 0)
			{
				$keys = array_keys($this->additionalColumns);
				
				for ($k = 0; $k < sizeof($keys); $k++)
				{
					if ($this->additionalColumnsBefore[$keys[$k]] == false)
					{
						$contentProvider = &$this->additionalColumns[$keys[$k]];
						
						$result['rows'][$recordId][] = $contentProvider->getHTML($recordId, $this->mainTable, $row);
						
						unset($contentProvider);
					}
				}
			}


			if ($this->allowView)
			{
				$viewButton = &$this->getViewEditDeleteLinkButton($this->viewHandlerClass, $recordId, 'View', $recordId . '_v', true);
	
				$result['rows'][$recordId][] = $viewButton->getHTML();
				
				unset($viewButton);
			}
			
			
			if ($this->allowEdit)
			{
				$editButton = &$this->getViewEditDeleteLinkButton($this->editHandlerClass, $recordId, 'Edit', $recordId . '_e', true);
				$result['rows'][$recordId][] = $editButton->getHTML();
				
				unset($editButton);
			}
			
			if ($this->allowDelete)
			{
				$deleteButton = &$this->getViewEditDeleteLinkButton($this->deleteHandlerClass, $recordId, 'Delete', $recordId . '_d', true);
				$result['rows'][$recordId][] = $deleteButton->getHTML();
				
				unset($deleteButton);
			}
	
		}

				
		return json_encode($result);
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
		$html = '';
		
		if ($this->numRecords > $this->defaultNumRowsPerPage)
		{
			$html .= ' <table border="0" cellspacing="' . $this->cellspacing . '" cellpadding="' . $this->cellpadding . '" class="ccSummaryControl">' . "\n";
	
			$html .= '  <tr><td>' . "\n";


			$list = $this->jumpToPageList->getOptions();
			
			$pageNumber = $this->getPageNumber();
			
			$html .= 'Page: ';
			
			$html .= '<select id="ccDynamicPageSelector-' . $this->getName() . '" onChange="jumpToPage(\'' . $this->getName() . '\')">';

			for ($i = 0; $i < sizeof($list); $i++)
			{
				if ($pageNumber == ($i + 1))
				{
					$html .= '<option selected value="' . ($i + 1) . '">' . $list[$i] . '</option>';
				}
				else
				{
					$html .= '<option value="' . ($i + 1) . '">' . $list[$i] . '</option>';
				}
			}
			
			$html .= '</select></td><td align="right">';

			$html .= $this->pluralDisplayName . ' Per Page: ' . $this->numberRowsPerPageList->getHTML();

			$html .= "</td></tr></table>\n";
		}

		return $html;
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
		global $application;
						
		//if ($this->numRecords > 0 && !$this->hasErrorMessage())
		// TODO come up with an "error message" for no records! ... we need to the table id (and column headers) here so we can apply filters.
		if (!$this->hasErrorMessage())
		{
			$numColumns = sizeof($this->additionalColumns) + sizeof($this->columnNames);
			if ($this->allowView) $numColumns++;
			if ($this->allowEdit) $numColumns++;
			if ($this->allowDelete) $numColumns++;
			if (sizeof($this->multipleSelectionsButtons) > 0) $numColumns++;
			
			// page number/records per page
			echo '<div id="' . $this->getName() . '_controlRow">';
			echo $this->getSummaryControlRow();
			echo '</div>';
			
			// the previous/next/refresh/add buttons.
			$this->getStatusRow();
			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// add Javascript for the multiple selection checkbox
			//
			
			if (sizeof($this->multipleSelectionsButtons) > 0)
			{
				echo $this->getIncludeMultipleSelectionsSelectAllJavascript();
			}
			
			echo '<table border="0" cellspacing="' . $this->cellspacing . '" cellpadding="' . $this->cellpadding . '" class="' . $this->style . '" id="' . $this->getName() . '">' . "\n";
			
			echo " <thead>\n";
			
			// the column headers
			echo '  <tr class="ccSummaryHeadings">' . "\n";
			
			
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
			$sortButtons = array();
			
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
						$sortHandler = new CC_Summary_Sort_Handler($this, $this->columnNames[$i]);
						
						$button = new CC_Sort_Button($displayName, $sortHandler);

						$script = "sortColumn('" . $this->getName() ."','" . $this->columnNames[$i] . "','" . $this->sortByDirection . "'); return false;";

						$button->setOnClick($script);

						$sortHandler->setSortButton($button);
						
						//$button->registerHandler($sortHandler);
						
						$this->window->registerButton($button);
						$this->sortButtons[$displayName] = &$button;
					}
				
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
					
					
					
					echo'   <th id="' . $this->getName() . '_heading_'. $this->columnNames[$i] . '" class="' . strtolower(preg_replace('/[ _]/', '-', $this->columnNames[$i])) . '">' . $button->getHTML() . "</th>\n";

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
				echo '    <th style="text-align:center"><img src="/N2O/CC_Images/cc_summary.view.png" width="16" height="16" title="View"></th>' . "\n"; 
			}
			if ($this->allowEdit)
			{
				echo '    <th style="text-align:center"><img src="/N2O/CC_Images/cc_summary.edit.png" width="16" height="16" title="Edit"></th>' . "\n"; 
			}
			if ($this->allowDelete)
			{
				echo '    <th style="text-align:center"><img src="/N2O/CC_Images/cc_summary.delete.png" width="16" height="16" title="Delete"></th>' . "\n"; 
			}
			echo " </tr>\n";

			echo " </thead>\n";
			echo " <tbody>\n";
			
			$shadeRow = false;
			
			if ($this->numRecords == 0)
			{
				echo '<td>No ' . $this->pluralDisplayName . '</td>';
			}
			else
			{
				for ($k = 0; $k <= ($this->getEndRowNumber() - $this->getStartRowNumber()); $k++)
				{
					echo $this->getRowHTML($k, $shadeRow);
					
					$shadeRow = !$shadeRow;
				}
			}
	
			echo "</tbody>\n";
			
			echo "</table>\n";

			if ($this->numRecords > 25 && $this->getNumRowsPerPage() > 25)
			{
				// the previous/next/refresh/add buttons.
				$this->getStatusRow();
			}
			
			// get the download/download All and multiple select buttons if they are
			// included.
			$this->getExtraSummaryButtons();
			
			$this->getJavascript();
		}
		// there are no records
		/*
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
		*/
	}
	

	//-------------------------------------------------------------------
	// METHOD: getJavascript()
	//-------------------------------------------------------------------

	/** 
	  * Javascript methods needed for dynamic functionality
	  *
	  * @access public
	  * @return javascript methods...
	  */

	function getJavascript()
	{
?>
<!--[if IE]>
<script language="JavaScript">
var summary = document.getElementById('<?php echo $this->getName(); ?>');
var tags = summary.getElementsByTagName('a');
for (i = 0; i < tags.length; i++)
{
	tags[i].attachEvent('onmouseover', function() { window.event.srcElement.parentNode.parentNode.className = "hover"; });
	tags[i].attachEvent('onmouseout', function() { window.event.srcElement.parentNode.parentNode.className = (i % 2 == 0 ? "even" : "odd"); });
}
</script>
<![endif]-->
<script type="text/javascript" src="/N2O/javascript/cc_dynamic_summary.js"></script>
<script type="text/javascript">

var setInterval(function()
{
	var summary = "<?php echo $this->getName(); ?>";
	var summaryTable = $(summary);
	var body = summaryTable.getElement('tbody');
	var reloadCallback = function(result)
	{
		if (result.success)
		{
			printSummaryTable(summary, body, result);
		}
		else
		{
			body.innerHTML = "Loading Failed";
		}
	};

	var jSonRequest = new Json.Remote("?method=refresh&summary=" + summary, { onComplete: reloadCallback }).send();
}, 60000);

</script>
<?php
	}
	
}

?>