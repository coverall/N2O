<?php
// $Id: CC_Safe_Summary.php,v 1.14 2008/07/23 02:28:35 jamie Exp $
//=======================================================================
// CLASS: CC_Safe_Summary
//=======================================================================

/**
 * This class provides a safe way to use a CC_Summary. Rather than using count(*) to obtain the number of rows in a summary, we will use the ENTIRE query. This is helpful when we have a query with groups and what not.
 *
 * @package CC_Summaries
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */
	 
class CC_Safe_Summary extends CC_Summary
{	

	/**
     * A where clause to be appended to the query we are using.
     *
     * var string _whereClause
     * @access private
     */	

	var $_whereClause;



	/**
     * A group clause to be appended to the query we are using.
     *
     * var string _groupClause
     * @access private
     */	

	var $_groupClause;



	//-------------------------------------------------------------------
	// METHOD: setWhereClause()
	//-------------------------------------------------------------------

	/** 
	  * Add a 'where' clause to your query, this will appended in the update method.
	  *
	  * @access public
	  * @param string $whereClause The where clause string.
	  */

	function setWhereClause($whereClause)
	{
		$this->_whereClause = $whereClause;
	}
	

	//-------------------------------------------------------------------
	// METHOD: setGroupClause()
	//-------------------------------------------------------------------

	/** 
	  * This method initilaizes many of the parameters of the CC_Summary and syncronizes them with the database. It is called in the constructor and is triggered by hitting the summary's refresh button.
	  *
	  * @access public
	  * @param string $groupClause The group clause string.
	  */

	function setGroupClause($groupClause)
	{
		$this->_groupClause = $groupClause;
	}


	//-------------------------------------------------------------------
	// METHOD: update()
	//-------------------------------------------------------------------

	/** 
	  * This method initilaizes many of the parameters of the CC_Summary and syncronizes them with the database. It is called in the constructor and is triggered by hitting the summary's refresh button.
	  *
	  * @access public
	  * @param string $displayName The display name.
	  * @param string $pluralDisplayName The plural display name.
	  */

	function update($force = false)
	{
		$this->clearErrorMessage();
		
		// Only do the update if it hasn't been done in the last two seconds...
		if ($force || (time() - $this->_lastUpdateTime) > $this->_updateTimeout)
		{
			global $application;
	
			$fullQuery = $this->query;
			
			$fullQuery .= (($this->_whereClause != NULL) ? ' ' . $this->_whereClause : '');
			$fullQuery .= (($this->_groupClause != NULL) ? ' ' . $this->_groupClause : '');
	
			$results = $application->db->doSelect($fullQuery);
			
			if (PEAR::isError($results))
			{
				$this->setErrorMessage('Error in query: ' . $fullQuery . ' (' . $results->getMessage() . ')');
				return;
			}
	
			$this->numRecords = cc_num_rows($results);
			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	
			if ($this->sortByColumn != NULL)
			{
				$fullQuery .= ' order by ' . $this->sortByColumn . ' ' .  $this->sortByDirection;
			}
	
			$fullQuery .=  ' limit ' .  $this->getNumRowsPerPage() . ' offset ' . ($this->getStartRowNumber() - 1);
	
			$results = $application->db->doSelect($fullQuery);
			
			// pagination updates
	
			if (PEAR::isError($results))
			{
				$this->setErrorMessage('Error in query: ' . $fullQuery . ' (' . $results->getMessage() . ')');
				return;
			}
	
			// if there are fewer records than the starting record number, set page back to 1
			if ($this->numRecords < $this->getStartRowNumber())
			{
				$this->pageNumber = 1;
			}
			
			//update jump to page Auto-Submit Field 
			$this->updateJumpToPageList();
					
			//update start and end rows
			$this->updateStartRowNumber();
			$this->updateEndRowNumber();
			
			//update download summary query
			//$this->downloadSummaryQuery = $this->query;
			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			
			unset($this->rows);
			$this->rows = array();
			
			$first = true;
			
			while ($row = cc_fetch_assoc($results))
			{
				if ($first)
				{
					$this->columnNames = array_keys($row);
					$first = false;
				}
	
				$this->rows[] = $row;
				
				unset($row);
			}
			
			unset($fullQuery);
			unset($first);

			$this->_lastUpdateTime = time();
		}
	}
	
	//-------------------------------------------------------------------
	// METHOD: getDownloadAllQuery
	//-------------------------------------------------------------------
	
	/**
	 * This method gets the download all query, if it doesn't exist, it will just get the regular query
	 *
	 * @access public
	 * @param bool $sort Return the sorted summary or not.
	 */

	function getDownloadAllQuery($sort = false)
	{
		$query = $this->downloadAllQuery;
		
		$query .= (($this->_whereClause != NULL) ? ' ' . $this->_whereClause : '');
		$query .= (($this->_groupClause != NULL) ? ' ' . $this->_groupClause : '');

		if ($sort && ($this->sortByColumn != NULL) && (!$this->downloadQueryComplete))
		{
			$query .= ' order by ' . $this->getSortByColumn() . ' ' . $this->sortByDirection;
		}
		
		return $query;
	}
}

?>