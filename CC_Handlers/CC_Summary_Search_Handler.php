<?php
// $Id: CC_Summary_Search_Handler.php,v 1.20 2008/07/23 02:28:35 jamie Exp $
//=======================================================================
// CLASS: CC_Summary_Search_Handler
//=======================================================================

/**
 * This CC_Action_Handler is used by the CC_Summary_Search_Compoment
 * class to alter the query of a CC_Summary.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Summary_Search_Compoment
 */

class CC_Summary_Search_Handler extends CC_Action_Handler
{			
	var $_summary;
	var $_originalQuery;
	var $_originalDownloadAllQuery;
	var $_searchField;
	var $_columns;
	var $_searchComponent;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Summary_Search_Handler
	//-------------------------------------------------------------------

	/**
	 * The constructor... Not sure what else to say.
	 *
	 * @access public
	 * @param CC_Summary $summary The summary on which to perform the search.
	 * @param CC_Text_Field $searchField The search field.
	 * @param array An array of column names to search through.
	 *
	 */

	function CC_Summary_Search_Handler(&$searchComponent, $columns)
	{
		$this->CC_Action_Handler();
		
		$this->_searchComponent = &$searchComponent;
		$this->_summary = &$searchComponent->_summary;
		$this->_originalQuery = $this->_summary->query;
		$this->_originalDownloadAllQuery = $this->_summary->downloadAllQuery;
		$this->_searchField = &$searchComponent->_searchField;
		$this->_columns = &$columns;
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method goes through each column in the columns array, and
	 * builds a where clause using the value of the search field.
	 *
	 * @access public
	 */

	function process()
	{
		$queryAddition = '';
		$downloadAllQueryAddition = '';
		
		$searchFieldString = $this->_searchField->getValue();
		$searchValues = explode($this->_searchComponent->_orDelimiter, $searchFieldString);
		
		if ((sizeof($searchValues) > 0) && !((sizeof($searchValues) == 1) && (strlen($searchValues[0]) == '')))
		{
			$application = &getApplication();
			
			$like = ($application->db->isPostgres()) ? 'ilike' : 'like';
			
			for ($j = 0; $j < sizeof($searchValues); $j++)
			{
				$searchValue = $searchValues[$j];
				
				if (strlen($searchValue) > 0)
				{
					//the first section
					if ($j == 0)
					{
						if (sizeof($searchValues) == 1)
						{
							$queryAddition .= (!stristr($this->_originalQuery, 'where') || $this->_searchComponent->_assumeNoWhereClause) ? ' where (' : ' and (';
							$downloadAllQueryAddition .= (!stristr($this->_originalDownloadAllQuery, 'where') || $this->_searchComponent->_assumeNoWhereClause) ? ' where (' : ' and (';
						}
						else
						{
							$queryAddition .= (!stristr($this->_originalQuery, 'where') || $this->_searchComponent->_assumeNoWhereClause) ? ' where ((' : ' and ((';
							$downloadAllQueryAddition .= (!stristr($this->_originalDownloadAllQuery, 'where') || $this->_searchComponent->_assumeNoWhereClause) ? ' where ((' : ' and ((';
						}
					}
					else
					{
						$queryAddition .= ' or (';
						$downloadAllQueryAddition .= ' or (';
					}
					
					$size = sizeof($this->_columns);
					for ($i = 0; $i < $size; $i++)
					{
						$column = $this->_columns[$i];
		
						$queryAddition .= $column . ' ' . $like  . ' \'%' . $searchValue . '%\'';
						$downloadAllQueryAddition .= $column . ' ' . $like  . ' \'%' . $searchValue . '%\'';
		
						if ($i + 1 < sizeof($this->_columns))
						{
							$queryAddition .= ' or ';
							$downloadAllQueryAddition .= ' or ';
						}
					}
					
					$queryAddition .= ')';
					$downloadAllQueryAddition .= ')';
						
					if ((sizeof($searchValues) > 1) && ($j == (sizeof($searchValues) - 1)))
					{
						$queryAddition .= ')';
						$downloadAllQueryAddition .= ')';
					}
				}
			}
			
			$queryAddition .= $this->getQueryAdditions();
			$downloadAllQueryAddition .= $this->getQueryAdditions();
		}
		else
		{
			$addition = $this->getQueryAdditions(true);
			
			if (strlen($addition))
			{
				$queryAddition .= (!stristr($this->_originalQuery, 'where') || $this->_searchComponent->_assumeNoWhereClause) ? ' where ' : ' and ';
				$queryAddition .= $addition;

				$downloadAllQueryAddition .= (!stristr($this->_originalDownloadAllQuery, 'where') || $this->_searchComponent->_assumeNoWhereClause) ? ' where ' : ' and ';
				$downloadAllQueryAddition .= $addition;
			}
		}
		
		$this->_summary->query = $this->_originalQuery . $queryAddition;
		$this->_summary->setDownloadAllQuery($this->_originalDownloadAllQuery . $downloadAllQueryAddition);
		
		$this->_summary->update(true);
	}


	//-------------------------------------------------------------------
	// METHOD: getQueryAdditions()
	//-------------------------------------------------------------------

	function getQueryAdditions($noAnd = false)
	{
		$fragment = '';
		
		$size = sizeof($this->_searchComponent->_queryAdditions);
		
		$andAddedToQuery = false;
		
		for ($i = 0; $i < $size; $i++)
		{
			$addition = &$this->_searchComponent->_queryAdditions[$i];
			
			$_addition = $addition->getQueryAddition();
			
			if (strlen($_addition))
			{
				$fragment .= (($noAnd && (!$andAddedToQuery || ($i == 0)) ) ? ' ' : ' and ') . '(' . $_addition . ')';
				$andAddedToQuery = true;
			}
			
			unset($_addition);
			unset($addition);
		}
		
		unset($size);
		
		return $fragment;
	}


}

?>