<?php
// $Id: CC_Ordered_Summary.php,v 1.26 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Ordered_Summary
//=======================================================================


/**
 * This class provides a visual means to display and manage tables in the database. It lists rows in a given table allowing users, based on their access, to view, edit and delete selected records. It also allows users to download tab-delimited, spreadsheet friendly representations of the contents of an N2O database for import into other software.
 *
 * Tables using this class must have a SORT_ID field defined in their tables which must not appear as the last column. Preferably, it should be defined immediately after the ID field.

SORT_ID int not null default 0,


and, in the CC_FIELDS table

insert into CC_FIELDS values ('SORT_ID', 'CC_Number_Field', 'Order', '', '', '');

 * @package CC_Summaries
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */
 
class CC_Ordered_Summary extends CC_Summary
{
	/**
     * An array used to keep track of the sort order for the summary.
     *
     * @var array $sortArray
     * @access private
     */	

	var $sortArray = array();
	
	/**
     * A handler class to execute when changing the sort order for the summary needs to be a child of CC_Action_Handler.
     *
     * @var string $sortHandlerClass
     * @access private
     */	
     
	var $sortHandlerClasses;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Ordered_Summary
	//-------------------------------------------------------------------

	/** 
	 * The CC_Ordered_Summary calls its parent constructor but then initializes the sorting structures to manage the summary's sort order.
	 *
	 * @access public
	 * @param string $name The unique name of the field. Names must be unique so that N2O knows which fields to update when users submit data.
	 * @param string $query The query to use.
	 * @param string $mainTable The main table that the summary's records belong to.
	 * @param bool $sortAscending Whether or not to sort the $sortByColumn in ascending order. Sorts descending if false.
	 * @param bool $allowView Whether or not users are allowed to view records.
	 * @param bool $allowEdit Whether or not users are allowed to edit records.
	 * @param bool $allowDelete Whether or not users are allowed to delete records.
	 * @param string $viewHandlerClass The name of the class to use for viewing records.
	 * @param string $editHandlerClass The name of the class to use for editing records.
	 * @param string $deleteHandlerClass The name of the class to use for deleting records.
	 * @param string $addHandlerClass The class to use for adding records.
	 * @param string $sortByColumn The column to sort by.
	 */

	function CC_Ordered_Summary($name, $query, $mainTable = NULL, $sortAscending = true, $allowView = false, $allowEdit = false, $allowDelete = false, $viewHandlerClass = 'CC_Summary_Record_Handler', $editHandlerClass = 'CC_Summary_Record_Handler', $deleteHandlerClass = 'CC_Summary_Record_Handler', $addHandlerClass = 'CC_Summary_Record_Handler', $sortByColumn = 'SORT_ID')
	{
		$this->CC_Summary($name, $query, $mainTable, $sortByColumn, $sortAscending, $allowView, $allowEdit, $allowDelete, $viewHandlerClass, $editHandlerClass, $deleteHandlerClass, $addHandlerClass);
		
		//build the sort array
		for ($i = 0; $i < sizeof($this->rows); $i++)
		{
			$currentRow = $this->rows[$i]; 
			
			$previousSortId = $currentRow['SORT_ID'];
			$id = $currentRow['ID'];
				
			$this->sortArray[$previousSortId] = $id;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: addAdditionalSortHandler()
	//-------------------------------------------------------------------

	/** 
	  * This method sets an additional handler method to execute on a sort 
	  * reorder
	  *
	  * @access private
	  * @return string The additional sort handler class;
	  */
	  
	function registerSortHandler($sortHandlerClass)
	{
		$this->sortHandlerClasses[] = $sortHandlerClass;
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
		global $application;
		
		$rowHTML = '  <tr class="ccSummaryData ' . ($shade ? 'even' : 'odd') . '" id="r' . $this->name . $rowNumber . '">' . "\n";
		
		$row = $this->rows[$rowNumber];
		
		if (array_key_exists('ID', $row))
		{
			$recordId = $row['ID'];
		}
		else
		{
			trigger_error('You didn\'t specify the ID field in your summary query! (Row: ' . $rowNumber . ') Are you using a GROUP BY clause? If so, you should use a CC_Safe_Summary instead.', E_USER_WARNING);
		}
		
		if (array_key_exists('SORT_ID', $row))
		{
			$sortId = $row['SORT_ID'];
		}
		else
		{
			trigger_error('CC_Ordered_Summary: you didn\'t specify the SORT_ID field in your summary query!', E_USER_WARNING);
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Iterate through the additional Columns
		//
		$rowHTML .= $this->getAdditionalColumnRowHTML($recordId, true);


		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// display the order column
		//
		
		$sortIdOptions = array();
		
		for ($m = 1; $m <= sizeof($this->rows); $m++)
		{
			$sortIdOptions[$m] = $m;
		}
		
		if (!($this->window->isFieldRegistered($this->name . '_' . $recordId)))
		{
			$selectListField = new CC_AutoSubmit_Select_Field($this->name . '_' . $recordId, 'Order', false, $sortId, '', $sortIdOptions);
		
			$selectListField->registerHandler(new CC_Summary_Reorder_Handler($this));
			
			for ($i = 0; $i < sizeof($this->sortHandlerClasses); $i++)
			{
				$sortHandlerClass = $this->sortHandlerClasses[$i];
				$sortHandler = new $sortHandlerClass($this->mainTable, $recordId, $this->displayName, $this->window, 'Go');
				
				if (is_a($sortHandler, 'CC_Action_Handler'))
				{
					$selectListField->registerHandler($sortHandler);
				}
			}
									
			$this->window->registerComponent($selectListField);
		}
		else
		{
			$selectListField = &$this->window->getField($this->name . '_' . $recordId);
		}
		
		$rowHTML .= "   <td align=\"center\">" . $selectListField->getHTML() . "</td>\n";
		

		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Iterate through the SQL results
		//
		
		//display the column ID only if the showIdColumn is true
		for ($l = 0; $l < sizeof($row); $l++)
		{
			if (!(($this->columnNames[$l] == 'ID') && ($this->showIdColumn == false)) && !($this->columnNames[$l] == 'SORT_ID'))
			{
				if (array_key_exists($this->columnNames[$l], $this->columnFilters))
				{
					$filter = &$this->columnFilters[$this->columnNames[$l]];

					if ($this->columnNames[$l] == $this->viewableColumn)
					{
						$viewButton = &$this->getViewEditDeleteLinkButton($this->viewHandlerClass, $recordId, $filter->processValue($row[$this->columnNames[$l]], $recordId), $recordId . "_customView");

						$rowHTML .= sprintf("   <td align=\"%s\">%s</td>\n", $filter->alignment, $viewButton->getHTML());
					}
					else
					{
						$rowHTML .= sprintf("   <td align=\"%s\">%s</td>\n", $filter->alignment, $filter->processValue($row[$this->columnNames[$l]], $recordId));
					}
				}
				else
				{
					if ($this->columnNames[$l] == $this->viewableColumn)
					{
						$viewButton = &$this->getViewEditDeleteLinkButton($this->viewHandlerClass, $recordId, $row[$this->columnNames[$l]], $recordId . "_customView");

						$rowHTML .= sprintf("   <td>%s</td>\n", $viewButton->getHTML());
					}
					else
					{
						$rowHTML .= sprintf("   <td>%s</td>\n", $row[$this->columnNames[$l]]);
					}
				}
			}
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Iterate through the additional Columns
		//
		$rowHTML .= $this->getAdditionalColumnRowHTML($recordId, false);
		
		if ($this->allowView)
		{
			$viewButton = &$this->getViewEditDeleteLinkButton($this->viewHandlerClass, $recordId, 'View', $recordId . '_view', true);
			$rowHTML .= sprintf("   <td align=\"center\">%s</td>\n", $viewButton->getHTML());
			
			unset($viewButton);
		}
		
		
		if ($this->allowEdit)
		{
			$editButton = &$this->getViewEditDeleteLinkButton($this->editHandlerClass, $recordId, 'Edit', $recordId . '_edit', true);
			$rowHTML .= sprintf("   <td align=\"center\">%s</td>\n", $editButton->getHTML());
			
			unset($editButton);
		}
		
		if ($this->allowDelete)
		{

			$deleteButton = &$this->getDeleteLinkButton($this->deleteHandlerClass, $recordId, 'Delete', $recordId . '_delete', true);
			$rowHTML .= sprintf("   <td align=\"center\">%s</td>\n", $deleteButton->getHTML());
			
			unset($deleteButton);
		}
		
		$rowHTML .= "  </tr>\n";
		
		unset($row);
		
		return $rowHTML;
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
	
	function &getDeleteLinkButton($handlerClass, $recordId, $label, $key, $image = false)
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
				$button = new CC_Image_Button($label, '/N2O/CC_Images/cc_summary.' . strtolower($label) . '.png', 16, 16);
			}
			else
			{
				$button = new CC_Text_Button($label);
			}
			
			$targetWindowMethod = 'get' . $label . 'Window';
			
			$button->registerHandler(new CC_Delete_Ordered_Confirm_Handler($this->mainTable, $recordId, $this->sortArray, $this->displayName));
			
			$button->setValidateOnClick(false);
			
			$this->buttons[$key] = &$button;
			
			$this->window->registerButton($button);
		}
		
		return $button;
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
		echo ' <table border="0" cellspacing="' . $this->cellspacing . '" cellpadding="' . $this->cellpadding . '" width="100%"><tr bgcolor="' . $this->backgroundColour . '"><td align="center">' . "\n";
		echo ' <table border="0" cellspacing="0" cellpadding="0">' . "\n";
		echo '  <tr>' . "\n";
		echo '   <td align="center" valign="middle">' . "\n";


		echo '&nbsp;&nbsp;Displaying <b>' . $this->numRecords . ' ' . $this->displayName;
		if ($this->numRecords != 1)
		{
			echo 's';
		}
		echo '</b>&nbsp;&nbsp;';
		
		echo '   </td>' . "\n";
		
		
		echo '   <td align="center" valign="middle">' . $this->refreshButton->getHTML() . "</td>\n";
		
		if ($this->includeAddButton)
		{
			echo '   <td align="center" valign="middle">' . $this->addNewButton->getHTML() . "</td>\n";
		}

		echo '  </tr>' . "\n";
		echo ' </table>' . "\n";
		echo ' </td></tr></table>' . "\n";
	}


	//-------------------------------------------------------------------
	// METHOD: getHTML()
	//-------------------------------------------------------------------

	/** 
	  * This method is used in the window to return the HTML for the CC_Ordered_Summary.
	  *
	  * @access public
	  * @return string The CC_Summary's display HTML.
	  */

	function getHTML()
	{
		global $application;
					
		if ($this->numRecords > 0 && !$this->hasErrorMessage())
		{
			//add one for the sort order
			$numColumns = sizeof($this->additionalColumns) + sizeof($this->columnNames) + 1;
			
			if ($this->allowView) $numColumns++;
			if ($this->allowEdit) $numColumns++;
			if ($this->allowDelete) $numColumns++;
			
			echo '<div class="' . $this->style . '">';

			// the previous/next/refresh/add buttons.
			$this->getStatusRow();
			
			
			// page number/records per page
			$this->getSummaryControlRow();
			

			echo '<table border="0" cellspacing="' . $this->cellspacing . '" cellpadding="' . $this->cellpadding . '" class="' . $this->style . '" id="' . $this->getName() . '">' . "\n";
			
			// the column headers
			echo '  <tr class="ccSummaryHeadings">' . "\n";
			

			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Iterate through the additional Columns
			//
			printf($this->getAdditionalColumnHTML(true));


			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// display the order column
			//

			echo "    <td align=\"center\">Order</td>\n";

			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Iterate through the SQL results
			//

			for ($i = 0; $i < sizeof($this->columnNames); $i++)
			{
				//display the column ID only if the showIdColumn is true
				if (!(($this->columnNames[$i] == 'ID') && ($this->showIdColumn == false)) && !($this->columnNames[$i] == 'SORT_ID'))
				{			
					$displayName = $application->fieldManager->getDisplayName($this->columnNames[$i]);
					
					printf('   <td>%s', $displayName);
					echo "</td>\n";
				}
			}
			

			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Iterate through the additional Columns
			//
			printf($this->getAdditionalColumnHTML(false));

			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Add view/edit/delete if appropriate
			//
			
			if ($this->allowView)
			{
				echo '    <td align="center">View</td>' . "\n"; 
			}
			if ($this->allowEdit)
			{
				echo '    <td align="center">Edit</td>' . "\n"; 
			}
			if ($this->allowDelete)
			{
				echo '    <td align="center">Delete</td>' . "\n"; 
			}
			
			echo "  </tr>\n";
			
			$shadeRow = FALSE;
			
			for ($k = 0; $k < $this->numRecords; $k++)
			{
				echo $this->getRowHTML($k, $shadeRow);
				
				$shadeRow = !$shadeRow;
			}
			
			echo "  </table></div>\n";
			
			// get the download/download All buttons if they are included.
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
	// METHOD: update()
	//-------------------------------------------------------------------

	/** 
	  * This method initilaizes many of the parameters of the CC_Ordered_Summary and syncronizes them with the database. It also updates the sort order parameters. It is called in the constructor and is triggered by hitting the summary's refresh button.
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
			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			
			//update the rows array
			$fullQuery = $this->query;
			
			if ($this->sortByColumn != NULL)
			{
				$fullQuery .= ' order by ' . $this->sortByColumn . ' ' .  $this->sortByDirection;
			}
			
			$results = $application->db->doSelect($fullQuery);		
			unset($this->rows);
			$this->rows = array();
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
			
			unset($fullQuery);
			unset($counter);
			
			//update the sort select lists and the sort array
			$this->sortArray = array();
			$sortIdOptions = array();
			
			for ($m = 1; $m <= $this->numRecords; $m++)
			{
				$sortIdOptions[$m] = $m;
			}
			
			for ($i = 0; $i < $this->numRecords; $i++)
			{
				$currentRow = $this->rows[$i];
				$recordId = $currentRow['ID'];
				$sortId = $currentRow['SORT_ID'];
				
				if ($this->window->isFieldRegistered($this->name . '_' . $recordId))
				{
					$selectListField = &$this->window->getField($this->name . '_' . $recordId);
					$selectListField->setOptions($sortIdOptions);
					$selectListField->setValue($sortId);
				}
				
				//update the sort array at the same time
				$this->sortArray[$sortId] = $recordId;
			}
		}
	}	
}

?>