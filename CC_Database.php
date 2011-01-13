<?php
// $Id: CC_Database.php,v 1.60 2004/11/29 21:21:07 patrick Exp $
//=======================================================================
// CLASS: CC_Database
//=======================================================================

/** This is N2O's database manager. It should not be constructed manually. It uses PEAR's DB library.
  *
  * @package CC_Managers
  * @access public
  * @author The Crew <n2o@coverallcrew.com>
  * @copyright Copyright &copy; 2003, Coverall Crew
  * @see http://pear.php.net/manual/en/package.database.php
  *
  */


define('DB_MYSQL', 4200);
define('DB_POSTGRES', 4201);

require_once('DB.php');

class CC_Database
{

	/**
     * The hostname where the database resides.
     *
     * @var string $_databaseHost
     * @access private
     */

	var $_databaseHost; 		// database hostname


	/**
     * The name of the database.
     *
     * @var string $_databaseHost
     * @access private
     */

	var $_databaseName; 		// the name of the mySQL database


	/**
     * The user used to access the database.
     *
     * @var string $_databaseUsername
     * @access private
     * @see $_databasePassword
     */

	var $_databaseUsername;


	/**
     * The password used to access the database.
     *
     * @var string $_databasePassword
     * @access private
     * @see $_databaseUsername
     */

	var $_databasePassword;


	/**
     * The password used to encode data in the database.
     *
     * @var string $_encodePassword
     * @access private
     */

	var $_encodePassword;


	/**
     * The type of database used in this application. Types are defined at the top of this file.
     *
     * @var int $_databaseType
     * @access private
     */

	var $_databaseType;


	/**
     * Whether or not we close our database connections.
     *
     * @var bool $_persistent
     * @access private
     */

	var $_persistent;

	
	/**
     * The PEAR DB object
     *
     * @var string $_db
     * @access private
     */

	var $_db;	// PEAR DB object


	/**
     * The PEAR DB datasource connection string.
     *
     * @var string $_datasource
     * @access private
     */

	var $_datasource;
	
	
	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Database
	//-------------------------------------------------------------------

	/**
	 * The CC_Database constructor initializes the object with all the pertinent parameters needed for accessing the database. 
	 *
	 * @access private
	 * @param string $databaseHost The database hostname. 
	 * @param string $databaseName The name of the database. 
	 * @param string $databaseUsername The username used to access the database. 
	 * @param string $databasePassword The password used to access the database.
	 * @param string $encodePassword The password used to encode data in the database. 
	 * @param string $databaseType The type of the database which defaults to DB_MYSQL.
	 */

	function CC_Database($databaseHost, $databaseName, $databaseUsername, $databasePassword, $encodePassword, $databaseType = DB_MYSQL)
	{
		$this->_databaseHost = $databaseHost;
		$this->_databaseName = $databaseName;
		$this->_databaseUsername = $databaseUsername;
		$this->setPassword($databasePassword);
		$this->setEncodePassword($encodePassword);
		$this->_databaseType = ($databaseType ? $databaseType : DB_MYSQL);
		//$this->_persistent = ($persistent ? true : false);

		switch ($this->_databaseType)
		{
			case DB_POSTGRES:
				$databaseTypeString = 'pgsql';
			break;
			
			case DB_MYSQL:
			default:
				$databaseTypeString = 'mysql';
			break;
		}
		
		$this->_datasource = strSlide13($databaseTypeString . '://' . $databaseUsername . ':' . $databasePassword . '@' . $databaseHost . '/' . $databaseName);
	}
		
	
	//-------------------------------------------------------------------
	// METHOD: openDatabase
	//-------------------------------------------------------------------

	/**
	 * This method connects to and opens the database so that actions can be called on it. 
	 *
	 * @access private
	 */
	
	function openDatabase()
	{
		global $ccDatabaseAlertEmail;
		
		$this->_db = DB::connect(strSlide13($this->_datasource));
		
		if (DB::isError($this->_db))
		{
			switch ($this->_databaseType)
			{
				case 4200:
				{
					$dbType = 'MySQL';
				}
				break;
				
				case 4201:
				{
					$dbType = 'Postgres';
				}
				break;
				
				default:
				{
					$dbType = 'Unknown';
				}
			}
			
			$errorMessage = $this->_db->getMessage();
			
			trigger_error($errorMessage, E_USER_WARNING);
			
			if (!isset($ccDatabaseAlertEmail))
			{
				$ccDatabaseAlertEmail = 'support@coverallcrew.com';
			}
			
			$port = '';

			if ($_SERVER['SERVER_PORT'] == 443)
			{
				$addressPrefix = 'https://';
			}
			else
			{
				$addressPrefix = 'http://';

				if ($_SERVER['SERVER_PORT'] != 80)
				{
					$port = ':' . $_SERVER['SERVER_PORT'];
				}
			}
			
			$address = $addressPrefix. $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
			$email = <<<EOF
An error occurred while trying to connect to the $dbType database.

Error:   $errorMessage
Address: $address
DB Host: $this->_databaseHost
DB User: $this->_databaseUsername
DB Name: $this->_databaseName
User IP: {$_SERVER['REMOTE_ADDR']}

Someone better look into this!
EOF;
			
			//dbAlertEmail is set in CC_Config
			cc_mail($ccDatabaseAlertEmail, 'Database Error!', $email, array('From' => 'cc_database_bot@coverallcrew.com'));
		}
		
		return $this->_db;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: closeDatabase
	//-------------------------------------------------------------------

	/**
	 * This method closes the database. Called after an openDatabase call.
	 *
	 * @access private
	 * @return bool Whether or not the database was closed successfully.
	 * @see openDatabase()
	 */
	
	function closeDatabase()
	{
		if (DEBUG)
		{
			trigger_error('Closing database...', E_USER_WARNING);
		}

		$this->_db->disconnect();
		unset($this->_db);
		
		return true;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: isPostgres
	//-------------------------------------------------------------------
	
	/**
	 * This method returns true if the current database is Postgres.
	 *
	 * @access public
	 * @return boolean Is this a Postgres database?
	 */
	
	function isPostgres()
	{
		return ($this->_databaseType == DB_POSTGRES);
	}
	

	//-------------------------------------------------------------------
	// METHOD: isMysql
	//-------------------------------------------------------------------
	
	/**
	 * This method returns true if the current database is MySQL.
	 *
	 * @access public
	 * @return boolean Is this a MySQL database?
	 */
	
	function isMysql()
	{
		return ($this->_databaseType == DB_MYSQL);
	}
	

	//-------------------------------------------------------------------
	// METHOD: doInsert
	//-------------------------------------------------------------------
	
	/**
	 * This method executes the given insert query on the database. The database is openeed and closed automatically. 
	 *
	 * @access public
	 * @param string $query The insert query to execute.
	 * @return int The id of the newly inserted record.
	 */
	
	function doInsert($query, $update = false, $function = __FUNCTION__)
	{
		global $application;
		
		if (DEBUG)
		{
			trigger_error('Query: ' . $query, E_USER_WARNING);
		}
		
		if (PEAR::isError($this->openDatabase()))
		{
			return $this->_db;
		}
		else
		{
			$this->begin();
			
			$result = $this->_db->query($query);
			
			if (DB::isError($result))
			{
				trigger_error('CC_Database::' . $function . '() ' . $result->getMessage() . ' Query: ' . $query, E_USER_WARNING);
				$this->rollback();

				$this->closeDatabase();
				return $result;
			}
			
			if ($update)
			{
				$return = $this->_db->affectedRows();
			}
			else
			{
				// somehow grab the tablename from the query.
				$queryArray = preg_split('/ /', $query);
				$return = $this->getLastInsertId($queryArray[2]);
			}
	
			$this->commit();
			$this->closeDatabase();
			
			return $return;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: doBatchInsert
	//-------------------------------------------------------------------
	
	/**
	 * This method executes a batch of insert queries on the database. The queries will all be part of a transaction, and should any query fail, the entire transaction will be rolled back (providing the underlying DB supports it).
	 *
	 * @access public
	 * @param array $queryArray Numeric array of queries.
	 * @return array The ids of the newly inserted record.
	 */
	
	function doBatchInsert($queryArray, $update = false, $function = __FUNCTION__)
	{
		global $application;
		
		$return = array();
		
		if (PEAR::isError($this->openDatabase()))
		{
			return $this->_db;
		}
		else
		{
			$this->begin();
			
			$size = sizeof($queryArray);
			
			for ($i = 0; $i < $size; $i++)
			{
				$result = $this->_db->query($queryArray[$i]);
				
				if (DB::isError($result))
				{
					trigger_error('CC_Database::' . $function . '() ' . $result->getMessage() . ' Query: ' . $queryArray[$i], E_USER_WARNING);
					$this->rollback();
	
					$this->closeDatabase();
					return $result;
				}
				else
				{
					if ($update)
					{
						$return[$i] = $this->_db->affectedRows();
					}
					else
					{
						// somehow grab the tablename from the query.
						$splitArray = preg_split('/ /', $queryArray[$i]);
						$return[$i] = $this->getLastInsertId($splitArray[2]);
					}
				}
			}
	
			$this->commit();
			$this->closeDatabase();
			
			return $return;
		}
	}
	

	//-------------------------------------------------------------------
	// METHOD: doBatchInsert
	//-------------------------------------------------------------------
	
	/**
	 * This method executes a batch of update queries on the database. The queries will all be part of a transaction, and should any query fail, the entire transaction will be rolled back (providing the underlying DB supports it).
	 *
	 * @access public
	 * @param array $queryArray Numeric array of queries.
	 * @return array The number of updated rows per query.
	 */
	
	function doBatchUpdate($queryArray)
	{
		return $this->doBatchInsert($queryArray, true, __FUNCTION__);
	}


	//-------------------------------------------------------------------
	// METHOD: getLastInsertId
	//-------------------------------------------------------------------
	
	/**
	 * This method retrieves the id of the latest record inserted into the database. 
	 *
	 * @access private
	 * @param string $tableName The table to check.
	 * @return int The id of the newly inserted record.
	 */

	function getLastInsertId($tableName, $dbAlreadyOpen = false)
	{
		global $application;
		
		switch ($this->_databaseType)
		{
			case DB_POSTGRES:
				$lastInsertIdQuery = 'SELECT last_value FROM ' . strtolower($tableName) . '_id_seq';
			break;
			
			case DB_MYSQL:
			default:
				$lastInsertIdQuery = 'SELECT LAST_INSERT_ID()';
			break;
		}
		
		$result = $this->_db->getOne($lastInsertIdQuery);
		
		if (DB::isError($result))
		{
			trigger_error('CC_Database::getLastInsertId() ' . $result->getMessage() . ' Query: ' . $lastInsertIdQuery, E_USER_WARNING);
		}
		
		return $result;
	}


	
	//-------------------------------------------------------------------
	// METHOD: doOrderedInsert
	//-------------------------------------------------------------------

	/**
	 * This method executes the given insert query on the database. Records are updated as necessary to maintain the records' order. 
	 *
	 * @access public
	 * @param string $query The insert query to execute.
	 * @param string $tableName The name of the table we are inserting into.
	 * @param int $sortId The position where the record should be inserted (which is also the value of the SORT_ID column).
	 * @return int The id of the newly inserted record.
	 */
	
	function doOrderedInsert($query, $tableName, $sortId)
	{
		global $application;
		
		if (DEBUG)
		{
			trigger_error('Insert Query: ' . $query, E_USER_WARNING);
		}
		
		if (PEAR::isError($this->openDatabase()))
		{
			return $this->_db;
		}
		else
		{
			$recordId = $this->doInsert($query);
			
			if (DB::isError($recordId))
			{
				trigger_error('CC_Database::doOrderedInsert() ' . $recordId->getMessage() . ' Query: ' . $query, E_USER_ERROR);
			}
	
			if ($sortId != 0)
			{
				$currentSortId = $sortId;
		
				while (true)
				{
					$query = 'update ' . $tableName . ' set SORT_ID="' . ($currentSortId + 1) . '" where SORT_ID="' . $currentSortId . '"';

					$result = $this->doUpdate($query);
					
					if (DB::isError($result))
					{
						break;
					}

					$currentSortId++;
				}
			}
			else //add at the end of the sort list
			{
				//get the number of records
				$numRecords = $this->doCount($tableName);
				
				$query = 'update ' . $tableName . ' set SORT_ID="' . $numRecords . '" where SORT_ID="0"';
				
				trigger_error('ordered update query is ' . $query, E_USER_WARNING);
				
				$this->doUpdate($query);
				
			}
			
			return $recordId;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: doQuery
	//-------------------------------------------------------------------

	/**
	 * This method executes the given query on the database.
	 *
	 * @access public
	 * @param string $query The query to execute.
	 * @return bool Whether or not the update was successful.
	 */
	
	function doQuery($query)
	{
		return $this->doUpdate($query);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: doUpdate
	//-------------------------------------------------------------------

	/**
	 * This method executes the given update query on the database.
	 *
	 * @access public
	 * @param string $query The update query to execute.
	 * @return integer The number of affected rows.
	 */
	
	function doUpdate($query)
	{
		return $this->doInsert($query, true, __FUNCTION__);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: doDelete
	//-------------------------------------------------------------------
	
	/**
	 * This method executes the given delete query on the database.
	 *
	 * @access public
	 * @param string $query The delete query to execute.
	 */

	function doDelete($query)
	{
		global $application;
		
		if (PEAR::isError($this->openDatabase()))
		{
			return $this->_db;
		}
		else
		{
			if (DEBUG)
			{
				trigger_error('Query: ' . $query, E_USER_WARNING);
			}

			$result = $this->_db->query($query);
		
			if (DB::isError($result))
			{
				trigger_error('CC_Database::doDelete() ' . $result->getMessage() . ' Query: ' . $query, E_USER_WARNING);
			}
			
			$this->closeDatabase();
			return $result;
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: doOrderedDelete
	//-------------------------------------------------------------------

	/**
	 * This method executes the given insert query on the database. Records are updated as necessary to maintain the records' order. 
	 *
	 * @access public
	 * @param string $tableName The name of the table we are inserting into.
	 * @param int $recordId The id of the record to delete.
	 * @param int $sortId The position of the record to delete (which is also the value of its SORT_ID column).
	 */
	
	function doOrderedDelete($tableName, $recordId, $sortId)
	{
		global $application;
		
		$query = 'delete from ' . $tableName . " where ID='" . $recordId . "'";

		if (DEBUG)
		{
			trigger_error('Query: ' . $query, E_USER_WARNING);
		}
		
		if (PEAR::isError($this->openDatabase()))
		{
			return $this->_db;
		}
		else
		{
			$result = $this->_db->query($query);
			
			if (DB::isError($result))
			{
				trigger_error('CC_Database::doOrderedDelete() ' . $result->getMessage() . ' Query: ' . $query, E_USER_WARNING);

				$this->closeDatabase();
				
				return $result;
			}
			else
			{
				$currentSortId = $sortId;
				
				do
				{
					$updateQuery = 'update ' . $tableName . ' set SORT_ID="' . $currentSortId . '" where SORT_ID="' . (++$currentSortId) . '"';
					
					$resultUpdate = $this->_db->query($updateQuery);		
								
					if (DB::isError($resultUpdate))
					{
						trigger_error($resultUpdate->getMessage() . ' Query: ' . $updateQuery, E_USER_WARNING);
					}
				}
				while ($this->_db->affectedRows() > 0);
				
				$this->closeDatabase();
			}
		}
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: doCount
	//-------------------------------------------------------------------
	
	/**
	 * This method counts records in a given table with a given where clause. 
	 *
	 * @access public
	 * @param string $tableName The name of the table from which we are counting records.
	 * @param string $whereClause The where clause to filter records. The whereClause is blank by default so all records in the table are counted if no whereClause is present.
	 * @return int The number of records that match the where clause.
	 */

	function doCount($tableName, $whereClause = '')
	{
		$countQuery = 'select count(*) from ' . $tableName;
		
		if ($whereClause != '')
		{
			$countQuery .= ' where ' . $whereClause;
		}
		
		$countResult = $this->doSelect($countQuery);
		$countRow = cc_fetch_row($countResult);
		
		return $countRow[0];
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: doSelect
	//-------------------------------------------------------------------

	/**
	 * This method executes the given select query on the database.
	 *
	 * @access public
	 * @param string $query The select query to execute.
	 * @return resource The result returned from the query. Use cc_fetch_row to cycle through the results.
	 */
	
	function doSelect($query)
	{
		$application = &getApplication();
		$start = time();

		if (PEAR::isError($this->openDatabase()))
		{
			return $this->_db;
		}
		else
		{
			$result = $this->_db->query($query);
			
			if (DEBUG)
			{
				trigger_error('Query: ' . $query . ' (' . (time() - $start) . ' seconds)', E_USER_WARNING);
			}
	
			if (DB::isError($result))
			{
				trigger_error('CC_Database::doSelect() ' . $result->getMessage() . ' Query: ' . $query, E_USER_WARNING);
			}
			
			$this->closeDatabase();		
			return $result;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: doGetOne
	//-------------------------------------------------------------------
	
	/**
	 * This method returns the first column of the first row in the given result set.
	 *
	 * @access public
	 * @param string $query The select statement query
	 * @return string The first column of the first row.
	 * @see doSelect()
	 */
	
	function doGetOne($query)
	{
		if (PEAR::isError($this->openDatabase()))
		{
			return $this->_db;
		}
		else
		{
			$start = time();
			
			$result = $this->_db->getOne($query);

			if (DEBUG)
			{
				trigger_error('Query: ' . $query . ' (' . (time() - $start) . ' seconds)', E_USER_WARNING);
			}

			if (DB::isError($result))
			{
				trigger_error('CC_Database::doGetOne() ' . $result->getMessage() . ' Query: ' . $query, E_USER_WARNING);
			}

			$this->closeDatabase();		

			return $result;
		}
	}

	
	//-------------------------------------------------------------------
	// METHOD: doGetRow
	//-------------------------------------------------------------------
	
	/**
	 * This method returns the first row in the given result set.
	 *
	 * @access public
	 * @param string $query The select statement query
	 * @return string The first column of the first row.
	 * @see doSelect()
	 */
	
	function doGetRow($query)
	{
		if (PEAR::isError($this->openDatabase()))
		{
			return $this->_db;
		}
		else
		{
			$start = time();
			
			$this->_db->setFetchMode(DB_FETCHMODE_ASSOC);
			$result = $this->_db->getRow($query);
			$this->_db->setFetchMode(DB_FETCHMODE_ORDERED);

			if (DEBUG)
			{
				trigger_error('Query: ' . $query . ' (' . (time() - $start) . ' seconds)', E_USER_WARNING);
			}

			if (DB::isError($result))
			{
				trigger_error('CC_Database::doGetRow() ' . $result->getMessage() . ' Query: ' . $query, E_USER_WARNING);
			}

			$this->closeDatabase();		

			return $result;
		}
	}

	
	//-------------------------------------------------------------------
	// METHOD: doPreparedSelect
	//-------------------------------------------------------------------

	/**
	 * A prepared query is an easy way to insert the data of an array into a query. For example, you could have a query, "insert into USERS (FIRSTNAME, LASTNAME, EMAIL) values (?, ?, ?)". By passing in this query, and an array containing the first name, last name, and email, the PEAR DB classes will parse the query, and replace each question mark with the quoted and escaped value of the array. This is useful because you don't have to manually construct and concatenate your query.
	 *
	 * @access public
	 * @see http://pear.php.net/manual/en/package.database.db.intro-execute.php
	 * @param string $query The select query to prepare.
	 * @param string $data A numeric array of your data to go into the query.
	 * @param bool $transaction When set to true, an error will cause the database to rollback (if supported).
	 * @return resource The result returned from the query. Use cc_fetch_row to cycle through the results.
	 * 
	 */
	
	function doPreparedSelect($query, $data, $transaction = false)
	{
		if (PEAR::isError($this->openDatabase()))
		{
			return $this->_db;
		}
		else
		{
			if ($transaction)
			{
				$this->begin();
			}
			
			$statement = $this->_db->prepare($query);

			$result = $this->_db->execute($statement, $data);
	
			if (DB::isError($result))
			{
				if ($transaction)
				{
					$this->rollback();
				}

				trigger_error('CC_Database::doSelect() ' . $result->getMessage() . ' Query: ' . $query, E_USER_WARNING);
				return false;
			}
			
			$this->closeDatabase();
			return $result;
		}
	}


	//-------------------------------------------------------------------
	// METHOD: begin
	//-------------------------------------------------------------------

	/**
	 * This method starts a database transaction. A transaction is a series of database queries. Should an error happen five queries into the transaction, you can "roll back" each of the previous queries, and restore the database to what it used to be. Should everything complete successfully, you need to "commit" your changes.
	 *
	 * @see rollback(),commit()
	 * @access public
	 * 
	 */
	
	function begin()
	{
		$this->_db->autoCommit(false);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: rollback
	//-------------------------------------------------------------------

	/**
	 * This method rolls back the last action(s) in the current transactions. This method will only work if you have started a transaction using begin().
	 *
	 * @see begin(), commit()
	 * @access public
	 * @return bool Whether the rollback was successful.
	 * 
	 */
	
	function rollback()
	{
		$result = $this->_db->rollback();

		$this->_db->autoCommit(true);
		
		if (DB::isError($result))
		{
			trigger_error('CC_Database::rollback() ' . $result->getMessage() . ' Query: ' . $query, E_USER_ERROR);
		}
		
		return true;
	}
	

	//-------------------------------------------------------------------
	// METHOD: commit
	//-------------------------------------------------------------------

	/**
	 * This method commits the last database actions in the current transaction. This method only works if you have started a transaction using begin().
	 *
	 * @see begin(), rollback()
	 * @access public
	 * @return bool Whether the commit was successful.
	 * 
	 */
	
	function commit()
	{
		$result = $this->_db->commit();

		$this->_db->autoCommit(true);
		
		if (DB::isError($result))
		{
			trigger_error('CC_Database::commit() ' . $result->getMessage(), E_USER_ERROR);
		}
		
		return true;
	}
	
		
	//-------------------------------------------------------------------
	// METHOD: getEncodePassword
	//-------------------------------------------------------------------

	/**
	 * This method gets the password used to encode data in the database.
	 *
	 * @access public
	 * @return string The database encode password.
	 */
	
	function getEncodePassword()
	{
		return strSlide13($this->_encodePassword);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setEncodePassword
	//-------------------------------------------------------------------

	/**
	 * This method sets the password used to encode data in the database.
	 *
	 * @access public
	 * @param string The database encode password.
	 */
	
	function setEncodePassword($encodePassword)
	{
		$this->_encodePassword = strSlide13($encodePassword);
	}
	
	
	
	//-------------------------------------------------------------------
	// METHOD: getPassword
	//-------------------------------------------------------------------

	/**
	 * This method gets the password used to connect to the database.
	 *
	 * @access public
	 * @return string The database password.
	 */
	
	function getPassword()
	{
		return strSlide13($this->_password);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setPassword
	//-------------------------------------------------------------------

	/**
	 * This method sets the password used to connect to the database.
	 *
	 * @access public
	 * @param string The database password.
	 */
	
	function setPassword($password)
	{
		$this->_password = strSlide13($password);
	}
	
	
	
	//-------------------------------------------------------------------
	// METHOD: cc_show_tables
	//-------------------------------------------------------------------

	/**
	 * This method returns an array of all the tables in the database.
	 *
	 * @access public
	 * @return array An array of all the database's table names.
	 */
	
	function cc_show_tables()
	{
		switch ($this->_databaseType)
		{
			case DB_POSTGRES:
				$query = "select tablename from pg_tables where schemaname='public'";
			break;
			
			case DB_MYSQL:
			default:
				$query = "show tables";
			break;
		}
	
		$result = $this->doSelect($query);
		
		$returnArray = array();
		
		while($row = cc_fetch_row($result))
		{
			$returnArray[] = $row[0];
		}
		
		return $returnArray;
	}


	//-------------------------------------------------------------------
	// METHOD: cc_get_fields
	//-------------------------------------------------------------------
	
	/**
	 * This method returns an array of all the fields in a given table.
	 *
	 * @access public
	 * @param string $table The name of the table to search.
	 * @return array An array of all the given table's field names.
	 */

	function cc_get_fields($table)
	{
		switch ($this->_databaseType)
		{
			case DB_POSTGRES:
				$query = 'SELECT a.attname, pg_catalog.format_type(a.atttypid, a.atttypmod) as type, a.attnotnull, a.atthasdef, adef.adsrc FROM pg_catalog.pg_attribute a LEFT JOIN pg_catalog.pg_attrdef adef ON a.attrelid=adef.adrelid AND a.attnum=adef.adnum where a.attrelid = (SELECT oid FROM pg_catalog.pg_class WHERE relname=\'' .  strtolower($table) . '\') AND a.attnum > 0 AND NOT a.attisdropped';
			break;
			
			case DB_MYSQL:
			default:
				$query = 'desc ' . $table;
			break;
		}
		
		$result = $this->doSelect($query);
		
		$returnArray = array();
			
		if (PEAR::isError($result))
		{
			trigger_error('Could not query ' . $table . ' (' . $result->getMessage() . ')', E_USER_ERROR);
		}
		else
		{
			while($row = cc_fetch_row($result))
			{
				$returnArray[] = $row[0];
			}
		}

		return $returnArray;
	}
}


//-------------------------------------------------------------------
// METHOD: cc_fetch_row
//-------------------------------------------------------------------

/**
 * This method returns a row in the given result set as a zero-indexed array. This is equivalent to mysql_fetch_row() or pgsql_fetch_row().
 *
 * @access public
 * @param resource $result A result set returned by doSelect.
 * @param int $fetchMode
 * @return array A zero-indexed array of all the data in the row.
 * @see doSelect()
 */

function cc_fetch_row(&$result, $fetchMode = DB_FETCHMODE_ORDERED)
{
	$row = $result->fetchRow($fetchMode);
	
	if (DB::isError($row))
	{
		return false;
	}
	else
	{
		return $row;
	}
}


//-------------------------------------------------------------------
// METHOD: cc_fetch_array
//-------------------------------------------------------------------

/**
 * This method returns a row in the given result set as a zero-indexed and associative array. This is equivalent to mysql_fetch_array() or pgsql_fetch_array().
 *
 * @access public
 * @param resource $result A result set returned by doSelect.
 * @return array An associative array of all the data in the row.
 * @see doSelect()
 *
 */

function cc_fetch_array(&$result)
{
	return cc_fetch_row($result, DB_FETCHMODE_ASSOC);
}


//-------------------------------------------------------------------
// METHOD: cc_fetch_assoc
//-------------------------------------------------------------------

/**
 * This method returns a row in the given result set as an associative array. This is equivalent to mysql_fetch_assoc() or pgsql_fetch_assoc().
 *
 * @access public
 * @param resource $result A result set returned by doSelect.
 * @return array An associative array of all the data in the row.
 * @see doSelect()
 * 
 */

function cc_fetch_assoc(&$result)
{
	return cc_fetch_row($result, DB_FETCHMODE_ASSOC);
}


//-------------------------------------------------------------------
// METHOD: cc_num_cols
//-------------------------------------------------------------------

/**
 * This method returns the number of columns returned in a result set.
 *
 * @access public
 * @param resource $result A result set returned by doSelect.
 * @return int The number of columns in the given result set.
 * @see doSelect()
 *
 */

function cc_num_cols(&$result)
{
	return $result->numCols();
}


//-------------------------------------------------------------------
// METHOD: cc_num_rows
//-------------------------------------------------------------------

/**
 * This method returns the number of rows returned in a result set.
 *
 * @access public
 * @param resource $result A result set returned by doSelect.
 * @return int The number of rows in the given result set.
 * @see doSelect()
 *
 */

function cc_num_rows(&$result)
{
	return $result->numRows();
}


//-------------------------------------------------------------------
// METHOD: cc_field_name
//-------------------------------------------------------------------

/**
 * This method mimics the mysql_field_name method. It gets the name of the specified field in a result.
 *
 * @access public
 * @param resource $result A result set returned by doSelect.
 * @param int $colNumber The index of the column for which we want the name.
 * @return string The column name at the given index in the given result set.
 * @see doSelect()
 * @todo Comment this properly. Mike?
 */

function cc_field_name(&$result, $colNumber)
{
	$fields = $result->tableInfo();
	
	return $fields[$colNumber][name];
}
?>